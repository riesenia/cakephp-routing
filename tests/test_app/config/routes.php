<?php
/**
 * This file is part of riesenia/routing package.
 *
 * (c) RIESENIA.com
 */
declare(strict_types=1);

use Cake\Routing\RouteBuilder;

return static function (RouteBuilder $routes) {
    require CONFIG . 'routes_compiled.php';
};
