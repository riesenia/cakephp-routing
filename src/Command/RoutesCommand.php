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
use Cake\Console\ConsoleOptionParser;
use Cake\Core\Configure;
use Kcs\ClassFinder\Finder\ComposerFinder;
use Riesenia\Routing\Attribute\Connect;
use Riesenia\Routing\Attribute\Resources;

class RoutesCommand extends Command
{
    /**
     * @var array<string,array<Resources|Connect>>
     */
    protected array $resources = [];

    public function execute(Arguments $args, ConsoleIo $io): void
    {
        $finder = (new ComposerFinder())
            ->inNamespace(\array_map(fn ($ns) => \trim($ns, '\\'), $args->getMultipleOption('namespace') ?? ['App']))
            ->path('Controller');

        foreach ($finder as $reflectedClass) {
            if (!$reflectedClass instanceof \ReflectionClass) {
                continue;
            }

            $className = \substr($reflectedClass->getShortName(), 0, -10);

            // add method attributes
            foreach ($reflectedClass->getMethods() as $method) {
                foreach ($method->getAttributes() as $attribute) {
                    $instance = $attribute->newInstance();

                    if (!$instance instanceof Connect) {
                        continue;
                    }

                    $methodName = $method->getName();

                    $instance->setName('/' . \strtolower($className));
                    $instance->setAction("{$className}::{$methodName}");

                    $this->addRoute($instance);
                }
            }

            // add class attributes
            foreach ($reflectedClass->getAttributes() as $attribute) {
                $instance = $attribute->newInstance();

                if (!$instance instanceof Resources) {
                    continue;
                }
                $instance->setName($className);
                $this->addRoute($instance);
            }
        }

        $controllers = \array_keys(\iterator_to_array($finder));

        if (empty($controllers)) {
            $io->error('No controllers found in the application.');

            return;
        }

        Configure::write('Routing.Controllers', $controllers);

        $phpCode = "<?php\n\$routes->setRouteClass(\\Cake\\Routing\\Route\\DashedRoute::class);\n\n";

        foreach ($this->resources as $key => $resources) {
            [$scope, $plugin] = \explode(',', $key);
            $plugin = $this->custom_var_export(['plugin' => $plugin]);

            $phpCode .= "\$routes->scope('{$scope}', {$plugin}, function (\\Cake\\Routing\\RouteBuilder \$builder) {\n";

            foreach ($resources as $value) {
                if ($value instanceof Resources) {
                    $options = $this->custom_var_export($value->getOptions());
                    $phpCode .= "    \$builder->resources('{$value->getName()}', {$options});\n";
                }

                if ($value instanceof Connect) {
                    $options = $this->custom_var_export($value->getOptions());
                    $phpCode .= "    \$builder->connect('{$value->getUri()}', '{$value->getAction()}',{$options});\n";
                }
            }

            $phpCode .= "});\n\n";
        }

        \file_put_contents($args->getArgument('output') ?? CONFIG . 'routes_compiled.php', $phpCode);
    }

    public function addRoute(Connect|Resources $route): void
    {
        $this->resources[$route->getScope() . ',' . $route->getPlugin()][] = $route;
    }

    public static function defaultName(): string
    {
        return 'routes:build';
    }

    protected function buildOptionParser(ConsoleOptionParser $parser): ConsoleOptionParser
    {
        $parser->setDescription('Build routes file from annotated controllers.')
            ->addArgument('output', [
                'help' => 'File to write routes to. Defaults to ' . CONFIG . 'routes_compiled.php.'
            ])
            ->addOption('namespace', [
                'short' => 'n',
                'help' => 'Namespace to search for controllers. Use multiple times for multiple namespaces. Searches in App namespace by default.',
                'multiple' => true
            ]);

        return $parser;
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
