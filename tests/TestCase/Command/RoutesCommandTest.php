<?php
/**
 * This file is part of riesenia/routing package.
 *
 * (c) RIESENIA.com
 */
declare(strict_types=1);

namespace Riesenia\Routing\Test\TestCase\Command;

use Cake\Console\TestSuite\ConsoleIntegrationTestTrait;
use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;

class RoutesCommandTest extends TestCase
{
    use ConsoleIntegrationTestTrait;
    use IntegrationTestTrait;

    public array $fixtures = [
        'plugin.Riesenia/Routing.Items',
        'plugin.Riesenia/Routing.ItemProducts',
        'plugin.Riesenia/Routing.Authors',
    ];

    public function testExecute()
    {
        $this->exec('routes:build -n Riesenia\Routing\App');

        // test overwritten routes
        $this->configRequest(['headers' => ['Accept' => 'application/json']]);
        $this->get('/items');
        $this->assertResponseCode(404);

        // test resource attribute
        $this->configRequest(['headers' => ['Accept' => 'application/json']]);
        $this->get('/items/1');
        $this->assertResponseCode(200);

        $this->configRequest(['headers' => ['Accept' => 'application/json']]);
        $this->delete('/items/1');
        $this->assertResponseCode(404);

        // test route attribute
        $this->configRequest(['headers' => ['Accept' => 'application/json']]);
        $this->get('/items/cool-item');
        $this->assertResponseCode(200);

        $body = \json_decode((string) $this->_response->getBody());
        $this->assertEquals(5, \count($body));
    }

    public function testWith2Namespaces()
    {
        $this->exec('routes:build -n Riesenia\Routing\App -n Riesenia\Core');

        // test  Riesenia\Routing plugin attribute
        $this->configRequest(['headers' => ['Accept' => 'application/json']]);
        $this->get('items/1');
        $this->assertResponseCode(200);

        // test  Riesenia\Core plugin attribute with api scope
        $this->configRequest(['headers' => ['Accept' => 'application/json']]);
        $this->get('api/authors');
        $this->assertResponseCode(200);

        $body = \json_decode((string) $this->_response->getBody());
        $this->assertEquals(2, \count($body));
        $this->assertResponseCode(200);

        $this->configRequest(['headers' => ['Accept' => 'application/json']]);
        $this->get('authors/1');
        $this->assertResponseCode(404);

        $this->configRequest(['headers' => ['Accept' => 'application/json']]);
        $this->get('api/authors/1');
        $this->assertResponseCode(200);

        // test  Riesenia\Core plugin attribute with admin scope
        $this->configRequest(['headers' => ['Accept' => 'application/json']]);
        $this->delete('admin/authors/1');
        $this->assertResponseCode(200);

        $this->configRequest(['headers' => ['Accept' => 'application/json']]);
        $this->delete('api/authors/1');
        $this->assertResponseCode(404);
    }

    public function testWithNewFileName()
    {
        $this->createRoutes(CONFIG . 'core_routes.php');

        $this->exec('routes:build -n Riesenia\Core ' . CONFIG . 'core_routes.php');

        // assert endpoints for core plugin
        $this->configRequest(['headers' => ['Accept' => 'application/json']]);
        $this->get('api/authors');
        $this->assertResponseCode(200);

        $this->configRequest(['headers' => ['Accept' => 'application/json']]);
        $this->get('api/authors/1');
        $this->assertResponseCode(200);

        $this->configRequest(['headers' => ['Accept' => 'application/json']]);
        $this->delete('admin/authors/1');
        $this->assertResponseCode(200);

        $this->configRequest(['headers' => ['Accept' => 'application/json']]);
        $this->delete('api/authors/1');
        $this->assertResponseCode(404);

        // assert endpoints for routing plugin
        $this->configRequest(['headers' => ['Accept' => 'application/json']]);
        $this->get('items/1');
        $this->assertResponseCode(404);
    }

    public function testNoControllerPrefix()
    {
        $this->exec('routes:build -n Riesenia\Routing\App');

        $this->configRequest(['headers' => ['Accept' => 'application/json']]);
        $this->get('/custom-item');
        $this->assertResponseCode(200);

        $body = \json_decode((string) $this->_response->getBody());
        $this->assertEquals(5, \count($body));

        $this->configRequest(['headers' => ['Accept' => 'application/json']]);
        $this->get('/items/custom-item');
        $this->assertResponseCode(404);

        $this->configRequest(['headers' => ['Accept' => 'application/json']]);
        $this->get('/items/no-uri-name');
        $this->assertResponseCode(200);

        $body = \json_decode((string) $this->_response->getBody());
        $this->assertEquals(5, \count($body));
    }

    public function testDashedRoutes()
    {
        $this->exec('routes:build -n Riesenia\Routing\App');

        $this->configRequest(['headers' => ['Accept' => 'application/json']]);
        $this->get('/item-products/1');
        $this->assertResponseCode(200);

        $this->configRequest(['headers' => ['Accept' => 'application/json']]);
        $this->get('/item-products');
        $this->assertResponseCode(200);

        $body = \json_decode((string) $this->_response->getBody());
        $this->assertEquals(2, \count($body));

        $this->configRequest(['headers' => ['Accept' => 'application/json']]);
        $this->get('/custom-index');
        $this->assertResponseCode(200);

        $body = \json_decode((string) $this->_response->getBody());
        $this->assertEquals(2, \count($body));

        $this->configRequest(['headers' => ['Accept' => 'application/json']]);
        $this->patch('/custom-index');
        $this->assertResponseCode(404);
    }

    public function testExtRoutes()
    {
        $this->exec('routes:build -n Riesenia\Routing\App');

        $this->configRequest(['headers' => ['Accept' => 'application/json']]);
        $this->patch('/item-products/add');
        $this->assertResponseCode(200);

        $this->patch('/item-products/add.json');
        $body = (string) $this->_response->getBody();
        $object = \json_decode($body);
        $this->assertResponseCode(200);
        $this->assertEquals($object, 'added');
    }

    private function createRoutes($file = CONFIG . 'routes_compiled.php'): void
    {
        if (!\file_exists(CONFIG . 'routes.php')) {
            \touch(CONFIG . 'routes.php');
        }

        \file_put_contents(CONFIG . 'routes.php', "<?php\nreturn static function (\\Cake\\Routing\\RouteBuilder \$routes) {\n    require '{$file}';\n};");

        if (!\file_exists($file)) {
            \touch($file);
        }
    }

    public function setUp(): void
    {
        parent::setUp();

        $this->setAppNamespace('Riesenia\Routing\App');

        // create routes files
        $this->createRoutes();
    }

    public function tearDown(): void
    {
        parent::tearDown();

        // remove routes files
        \array_map('unlink', \glob(CONFIG . '*routes*.php'));
    }
}
