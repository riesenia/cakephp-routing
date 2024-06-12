<?php
require_once __DIR__ . '/vendor/autoload.php';

$header = 'This file is part of riesenia/routing package.

(c) RIESENIA.com';

$finder = PhpCsFixer\Finder::create()
    ->exclude('vendor')
    ->in(__DIR__)
;

$config = new Rshop\CS\Config\Rshop($header);

$config->setStrict()->setFinder($finder);

return $config;
