## Overview
The `riesenia/cakephp-routing` package allows you to define routes using class attributes  in your controllers. These routes are then compiled into a `routes_compiled.php` file, which is included in your application's `routes.php` file.

## Configuration

if you want to add routes for controllers not in the default app namespace `APP`, pass `namespace` option to the command
```bash
routes:build -n Plugin
```

## Defining routes

1. Define your class routes using the `Resources` attribute.
```php
<?php
namespace App\Controller;
use Riesenia\Routing\Attribute\Resources;

#[Resources(only: ['index', 'view'])]
class AuthorsController extends AppController
{
}
```

2. Define your method routes using the `Connect` attribute.
```php
<?php
namespace App\Controller;
use Riesenia\Routing\Attribute\Connect;

class AuthorsController extends AppController
{
    #[Connect(uri: 'cool-item')]
    public function index()
    {
    }
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
