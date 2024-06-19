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
    ];

    public function testExecute()
    {
        $file = CONFIG . 'routes_compiled.php';

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
    }

    public function setUp(): void
    {
        parent::setUp();
        $this->setAppNamespace('Riesenia\Routing\App');
    }
}
