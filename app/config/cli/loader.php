<?php

$loader = new \Phalcon\Loader();

$loader->registerNamespaces(
    array(
        'App'                       => APP_PATH . "/",
        'App\\Tasks'                => APP_PATH . "/tasks/",
        'App\\Tasks\\Exception'     => APP_PATH . "/tasks/exception/",
    )
)->register();
//
$loader->registerDirs([
    APP_PATH . '/tasks',
    APP_PATH . '/models'
]);
$loader->register();
