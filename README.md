## Overview
The `riesenia/routing` package allows you to define routes using class attributes  in your controllers. These routes are then compiled into a `routes_compiled.php` file, which is included in your application's `routes.php` file.

## Configuration

if you want to add routes for controllers not in the default app namespace, add new key `namespaces`
```php
return [
    'Routing' => [
        'namespaces' => ['\\Plugin\\']
    ]
];
```

## Defining routes
Define your routes using the `Resource` attribute.
```php
<?php
namespace App\Controller;
use Riesenia\Routing\Attributes\Resource;

#[Resource(only: ['index', 'view'])]
class AuthorsController extends AppController
{
}
```

## Compiling Routes
Run the Routes Command:

This will compile your routes into a `routes_compiled.php` file.


## Using Compiled Routes
Update your `routes.php` to include the compiled routes.
```php
<?php
use Cake\Routing\RouteBuilder;

return static function (RouteBuilder $routes) {
    require CONFIG . 'routes_compiled.php';
};
