<?php
// Here you can initialize variables that will be available to your tests.
/**
 * AspectMock
 */
use AspectMock\Kernel;

$kernel = Kernel::getInstance();
$kernel->init([
    'debug'        => true,
    'includePaths' => [
        codecept_root_dir('src/'),
        codecept_root_dir('vendor/league/container/src/'),
    ],
]);
