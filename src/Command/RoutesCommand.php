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
use Kcs\ClassFinder\Finder\ComposerFinder;
use Riesenia\Routing\Attribute\Connect;
use Riesenia\Routing\Attribute\Resources;

class RoutesCommand extends Command
{
    public function execute(Arguments $args, ConsoleIo $io): void
    {
        $finder = (new ComposerFinder())
            ->useAutoloading(false)
            ->inNamespace(\array_map(fn ($ns) => \trim($ns, '\\'), $args->getMultipleOption('namespace') ?? $this->defaultNamespaces()))
            ->path('Controller');

        $routes = [];

        foreach ($finder as $reflectedClass) {
            if (!$reflectedClass instanceof \ReflectionClass) {
                continue;
            }

            $className = \substr($reflectedClass->getShortName(), 0, -10);

            // add class attributes (resources)
            foreach ($reflectedClass->getAttributes() as $attribute) {
                $instance = $attribute->newInstance();

                if (!$instance instanceof Resources) {
                    continue;
                }

                $instance->setController($className);

                $routes[$instance->getScope() . ',' . $instance->getPlugin()][] = $instance;
            }

            // add method attributes (connect)
            foreach ($reflectedClass->getMethods() as $method) {
                foreach ($method->getAttributes() as $attribute) {
                    $instance = $attribute->newInstance();

                    if (!$instance instanceof Connect) {
                        continue;
                    }

                    $instance->setController($className);
                    $instance->setAction($method->getName());
                    $instance->setParameters(\array_map(fn ($parameter) => $parameter->getName(), $method->getParameters()));

                    $routes[$instance->getScope() . ',' . $instance->getPlugin()][] = $instance;
                }
            }
        }

        $phpCode = "<?php\n\$routes->setRouteClass(\\Cake\\Routing\\Route\\DashedRoute::class);\n";

        foreach ($routes as $key => $routes) {
            [$scope, $plugin] = \explode(',', $key);

            $plugin = $plugin ? ", ['plugin' => '{$plugin}']" : '';

            $phpCode .= "\n\$routes->scope('{$scope}'{$plugin}, function (\\Cake\\Routing\\RouteBuilder \$builder) {\n";

            foreach ($routes as $value) {
                $phpCode .= '    ' . $value->phpCode() . "\n";
            }

            $phpCode .= "});\n";
        }

        \file_put_contents($args->getArgument('output') ?? CONFIG . 'routes_compiled.php', $phpCode);
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

    /**
     * @return string[]
     */
    protected function defaultNamespaces(): array
    {
        return ['App'];
    }
}
