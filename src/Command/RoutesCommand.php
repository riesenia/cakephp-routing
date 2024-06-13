<?php
/**
 * This file is part of riesenia/routing package.
 *
 * (c) RIESENIA.com
 */
declare(strict_types=1);

namespace Riesenia\Routing\Command;

use Cake\Command\Command;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\Core\Configure;
use MixerApi\Core\Utility\NamespaceUtility;
use Riesenia\Routing\Attributes\Resource;

class RoutesCommand extends Command
{
    /**
     * @var array<string,array<\Riesenia\Routing\Attributes\Resource>>
     */
    protected array $resources = [];

    public function addRoute(Resource $route): void
    {
        $this->resources[$route->getScope() . ',' . $route->getPlugin()][] = $route;
    }

    /**
     * Build routes to the controllers.
     *
     * @throws \ReflectionException
     *
     * @return array<string,array<\Riesenia\Routing\Attributes\Resource>>
     */
    public function getRoutes(): array
    {
        foreach ($this->getArray(Configure::read('Routing.Controllers')) as $controller) {
            if (!\is_string($controller) || !\class_exists($controller)) {
                continue;
            }

            $reflectedClass = new \ReflectionClass($controller);

            foreach ($reflectedClass->getAttributes() as $attribute) {
                $instance = $attribute->newInstance();

                if (!$instance instanceof Resource) {
                    continue;
                }

                $instance->setName(\substr($reflectedClass->getShortName(), 0, -10));
                $this->addRoute($instance);
            }
        }

        return $this->resources;
    }

    public static function defaultName(): string
    {
        return 'riesenia:routes';
    }

    public function execute(Arguments $args, ConsoleIo $io): void
    {
        $controllers = \array_reduce($this->getArray(Configure::read('Routing.namespaces', ['\\App\\'])), fn ($carry, $controllerNs) => \array_merge($carry, NamespaceUtility::findClasses($controllerNs . 'Controller')), []);

        if (empty($controllers)) {
            $io->error('No controllers found in the application.');

            return;
        }

        Configure::write('Routing.Controllers', $controllers);

        $phpCode = "<?php\n\$routes->setRouteClass(\\Cake\\Routing\\Route\\DashedRoute::class);\n\n";

        foreach ($this->getRoutes() as $key => $resources) {
            [$scope, $plugin] = \explode(',', $key);
            $plugin = $this->custom_var_export(['plugin' => $plugin]);
            $phpCode .= "\$routes->scope('{$scope}', {$plugin}, function (\\Cake\\Routing\\RouteBuilder \$builder) {\n";

            foreach ($resources as $value) {
                $options = $this->custom_var_export($value->getOptions());
                $phpCode .= "    \$builder->resources('{$value->getName()}', {$options});\n";
            }

            $phpCode .= "});\n\n";
        }

        \file_put_contents(CONFIG . 'routes_compiled.php', $phpCode);
    }

    /**
     * @return string[]
     */
    private function getArray(mixed $value): array
    {
        return \is_array($value) ? (array) $value : throw new \InvalidArgumentException('Value is not a string!');
    }

    private function custom_var_export(mixed $var): string
    {
        switch (\gettype($var)) {
            case 'array':
                $indexed = \array_keys($var) === \range(0, \count($var) - 1);
                $formatted = [];

                foreach ($var as $key => $value) {
                    $formatted[] = ($indexed ? '' : \var_export($key, true) . ' => ')
                        . $this->custom_var_export($value);
                }

                return '[' . \implode(', ', $formatted) . ']';

            default:
                return \var_export($var, true);
        }
    }
}
