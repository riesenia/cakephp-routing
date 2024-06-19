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
        'plugin.Riesenia/Routing.Authors',
    ];

    public function testExecute()
    {
        list($file, $routes) = $this->createFiles();

        $this->exec('routes:build -n Riesenia\Routing\App');
        $this->assertFileExists($file, 'routes_compiled file was not generated');

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
        $this->assertResponseCode(200);

        \unlink($file);
        \unlink($routes);
    }

    public function testWith2Namespaces()
    {
        list($file, $routes) = $this->createFiles();

        $this->exec('routes:build -n Riesenia\Routing\App -n Riesenia\Core');

        $this->assertFileExists($file, 'routes_compiled file was not generated');

        // test  Riesenia\Routing plugin attribute
        $this->configRequest(['headers' => ['Accept' => 'application/json']]);
        $this->get('items/1');
        $this->assertResponseCode(200);

        // test  Riesenia\Core plugin attribute with api scope
        $this->configRequest(['headers' => ['Accept' => 'application/json']]);
        $this->get('api/authors');
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

        \unlink($file);
        \unlink($routes);
    }

    public function testWithNewFileName()
    {
        list($file, $routes) = $this->createFiles(CONFIG . 'routes.php', CONFIG . 'core_routes.php');

        $this->exec('routes:build -n Riesenia\Core ' . $file);
        $this->assertFileExists($file, 'routes file was generated');
        $this->assertFileExists($routes, 'core routes file was generated');

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

        \unlink($file);
        \unlink($routes);
    }

    private function createFiles($file = CONFIG . 'routes.php', $compiled = CONFIG . 'routes_compiled.php')
    {
        if (!\file_exists($compiled)) {
            \touch($compiled);
        }

        if (!\file_exists($file)) {
            \touch($file);
        }
        \file_put_contents($file, "<?php\nreturn static function (\\Cake\\Routing\\RouteBuilder \$routes) {\n require '{$compiled}';\n};");

        return [$file, $compiled];
    }

    public function setUp(): void
    {
        parent::setUp();
        $this->setAppNamespace('Riesenia\Routing\App');
    }
}
