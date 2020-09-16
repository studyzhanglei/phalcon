<?php

$loader = new \Phalcon\Loader();

$loader->registerNamespaces(
    array(
        'Controllers\\Test'         => APP_PATH . "/controllers/test/",
//        'App\\Plugins'              => APP_PATH . "/plugins/",
        'App'                       => APP_PATH . "/",
//        'App\\Exception'            => APP_PATH . "/exception/",
//        'App\\Exception\\Handle'    => APP_PATH . "/exception/handle/",
    )
)->register();


/**
 * We're a registering a set of directories taken from the configuration file
 */
$loader->registerDirs(
    [
        $config->application->controllersDir,
        $config->application->modelsDir
    ]
)->register();

