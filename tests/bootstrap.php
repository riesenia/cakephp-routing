<?php
/**
 * This file is part of riesenia/routing package.
 *
 * (c) RIESENIA.com
 */
declare(strict_types=1);

use Cake\Core\Configure;

use function Cake\Core\env;

use Cake\Core\Plugin;
use Cake\Datasource\ConnectionManager;
use Cake\Log\Engine\FileLog;
use Cake\Log\Log;
use Cake\TestSuite\Fixture\SchemaLoader;
use Cake\Utility\Security;

if (!\defined('DS')) {
    \define('DS', DIRECTORY_SEPARATOR);
}
\define('ROOT', \dirname(__DIR__));
\define('APP_DIR', 'test_app');
\define('APP', ROOT . DS . 'tests' . DS . APP_DIR . DS);
\define('CONFIG', APP . 'config' . DS);
\define('WWW_ROOT', APP . 'webroot' . DS);
\define('TESTS', ROOT . DS . 'tests' . DS);
\define('TMP', ROOT . DS . 'tmp' . DS);
\define('LOGS', TMP . 'logs' . DS);
\define('CAKE_CORE_INCLUDE_PATH', ROOT . DS . 'vendor' . DS . 'cakephp' . DS . 'cakephp');
\define('CORE_PATH', CAKE_CORE_INCLUDE_PATH . DS);
\define('CAKE', CORE_PATH . 'src' . DS);
@\mkdir(TMP);
@\mkdir(LOGS);

require ROOT . DS . 'vendor' . DS . 'autoload.php';

require CORE_PATH . 'config' . DS . 'bootstrap.php';

// Ensure default test connection is defined
if (!\getenv('DATABASE_TEST_URL')) {
    \putenv('DATABASE_TEST_URL=sqlite:///:memory:');
}

$config = [
    'debug' => true,

    'App' => [
        'namespace' => 'Riesenia\Routing\App',
        'encoding' => env('APP_ENCODING', 'UTF-8'),
        'defaultLocale' => env('APP_DEFAULT_LOCALE', 'en_US'),
        'base' => false,
        'dir' => 'src',
        'webroot' => 'webroot',
        'wwwRoot' => WWW_ROOT,
        'fullBaseUrl' => 'http://localhost',
        'imageBaseUrl' => 'img/',
        'cssBaseUrl' => 'css/',
        'jsBaseUrl' => 'js/',
        'paths' => [
            'plugins' => [ROOT . DS . 'plugins' . DS],
            'templates' => [APP . 'Template' . DS],
            'locales' => [APP . 'Locale' . DS]
        ],
    ],
    'Routing' => [
        'namespaces' => ['\Riesenia\Routing\App']
    ],
    'Datasources' => [
        'test' => ['url' => \getenv('DATABASE_TEST_URL')]
    ],
    'Security' => [
        'salt' => env('SECURITY_SALT', 'drjm0kHByfhtPOnsVJmS4YpZxEjI0O//Me5hIV6V0nw='),
    ],
    'Error' => [
        'errorLevel' => E_ALL & ~E_DEPRECATED,
        'exceptionRenderer' => 'Cake\Error\ExceptionRenderer',
        'skipLog' => [],
        'log' => true,
        'trace' => true,
    ],
    'Log' => [
        'debug' => [
            'className' => FileLog::class,
            'path' => LOGS,
            'file' => 'debug',
            'levels' => ['notice', 'info', 'debug'],
            'url' => env('LOG_DEBUG_URL', null),
        ],
        'error' => [
            'className' => FileLog::class,
            'path' => LOGS,
            'file' => 'error',
            'levels' => ['warning', 'error', 'critical', 'alert', 'emergency'],
            'url' => env('LOG_ERROR_URL', null),
        ],
    ],
    'Session' => [
        'defaults' => 'php',
    ],
];

Configure::write($config);

\date_default_timezone_set('UTC');
\mb_internal_encoding(Configure::read('App.encoding'));
\ini_set('intl.default_locale', Configure::read('App.defaultLocale'));

ConnectionManager::setConfig(Configure::consume('Datasources'));
Log::setConfig(Configure::consume('Log'));
Security::setSalt(Configure::consume('Security.salt'));

// Create test database schema
if (env('FIXTURE_SCHEMA_METADATA')) {
    (new SchemaLoader())->loadInternalFile(env('FIXTURE_SCHEMA_METADATA'));
}

// load the plugin
Plugin::getCollection()->add(new \Riesenia\Routing\Plugin());
Plugin::getCollection()->add(new \Riesenia\Core\Plugin());
