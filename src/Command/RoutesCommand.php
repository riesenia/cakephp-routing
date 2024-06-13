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
use Riesenia\Routing\Router;

class RoutesCommand extends Command
{
    public function execute(Arguments $args, ConsoleIo $io): void
    {
        $namespaces = Configure::read('Routing.namespaces');

        foreach ($namespaces as $controllerNs) {
            $controllers = NamespaceUtility::findClasses($controllerNs . 'Controller');
            \var_dump($controllers);
        }
        $controllerFiles = \glob(APP . 'Controller/*Controller.php');

        if ($controllerFiles === false) {
            $io->error('No controllers found in the application.');

            return;
        }

        $controllers = \array_map(fn ($controllerFile) => 'App\\Controller\\' . \basename($controllerFile, '.php'), $controllerFiles);
        Configure::write('Routing.Controllers', $controllers);

        $phpCode = "<?php\n\$routes->setRouteClass(\\Cake\\Routing\\Route\\DashedRoute::class);\n\n";

        foreach ((new Router())->getRoutes() as $key => $resources) {
            $phpCode .= "\$routes->scope('{$key}', function (\\Cake\\Routing\\RouteBuilder \$builder) {\n";

            foreach ($resources as $value) {
                $options = $this->exportOptions($value->getOptions());
                $phpCode .= "    \$builder->resources('{$value->getName()}', {$options});\n";
            }

            $phpCode .= "});\n\n";
        }
        \file_put_contents(CONFIG . 'routes_compiled.php', $phpCode);
    }

    /**
     * @param array{only: string[], map: array{method?: string, path?: string, action?: string}, path: string|null} $options
     */
    private function exportOptions(array $options): string
    {
        $formattedOptions = [];

        foreach ($options as $key => $value) {
            // Format the value as a string
            if (\is_array($value)) {
                // If the value is an array, format it properly
                $formattedValue = '[' . \implode(', ', \array_map(fn ($v) => \var_export($v, true), $value)) . ']';
            } else {
                // Otherwise, directly export it
                $formattedValue = \var_export($value, true);
            }

            // Add the key-value pair to the formatted options array
            $formattedOptions[] = "'{$key}' => {$formattedValue}";
        }

        // Combine all formatted options into a single string
        return '[' . \implode(', ', $formattedOptions) . ']';
    }
}
