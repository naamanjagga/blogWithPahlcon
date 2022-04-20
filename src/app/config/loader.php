<?php

$loader = new \Phalcon\Loader();

/**
 * We're a registering a set of directories taken from the configuration file
 */
$loader->registerDirs(
    [
        $config->application->controllersDir,
        $config->application->modelsDir,
        $config->application->componentsDir
    ]
)->register();

$loader->registerNamespaces(
    [
        'App\Components' => APP_PATH . '/components/',
        'App\Handler' => APP_PATH . '/handler/'
    ]
);
$loader->register();