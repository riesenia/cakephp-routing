<?php
/**
 * This file is part of riesenia/routing package.
 *
 * (c) RIESENIA.com
 */
declare(strict_types=1);

if (!\defined('DS')) {
    \define('DS', DIRECTORY_SEPARATOR);
}
\define('ROOT', \dirname(__DIR__));
\define('APP_DIR', 'src');
\define('APP', ROOT . DS . APP_DIR . DS);
\define('CONFIG', ROOT . DS . 'config' . DS);
