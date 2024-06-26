<?php
/**
 * This file is part of riesenia/routing package.
 *
 * (c) RIESENIA.com
 */
declare(strict_types=1);

namespace Riesenia\Routing;

use Cake\Core\BasePlugin;

class Plugin extends BasePlugin
{
    protected bool $bootstrapEnabled = false;

    protected bool $middlewareEnabled = false;

    protected bool $servicesEnabled = false;

    protected bool $routesEnabled = false;
}
