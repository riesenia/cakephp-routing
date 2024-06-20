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

When defining routes using the `Connect` attribute in PHP, adding `/` to the URI will stop prefixing your route with the controller name.
```php
<?php
namespace App\Controller;
use Riesenia\Routing\Attribute\Connect;

class AuthorsController extends AppController
{
    #[Connect(uri: 'cool-author')]
    public function index()
    {
        // Controller logic for /authors/cool-author
    }

    #[Connect(uri: '/custom-author')]
    public function custom()
    {
        // Controller logic for /custom-author
    }
}
```
#### Resulting Routes:

```php
$builder->connect('/authors/cool-author', 'Authors::index',[]);
$builder->connect('/custom-author', 'Authors::custom',[]);
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
