<?php

$group  = new \Phalcon\Mvc\Router\Group();
$router = $di->getRouter();

// Define your routes here

$router->setDefaults(
    [
        "namespace"  => "App\\Controllers\\Test",
        "controller" => "index",
        "action"     => "index",
    ]
);

$group->setPrefix("/api");


$group->add(
    "/index/:params",
    array(
        "namespace"  => "App\\Controllers\\Test",
        "controller" => "index",
        "action"     => "index",
        "params"     => 1
    ),
    ['GET', "POST"]
);

$group->add(
    "/:controller/:action/:params",
    array(
        "namespace"  => "App\\Controllers\\Test",
        "controller" => 1,
        "action"     => 2,
        "params"     => 3,
    ),
    ['GET', "POST"]
);
$router->add(
    "/:controller/:action/:params",
    array(
        "namespace"  => "App\\Controllers\\Test",
        "controller" => 1,
        "action"     => 2,
        "params"     => 3,
    ),
    ['GET', "POST"]
);


$router->mount($group);

$router->handle($_SERVER['REQUEST_URI']);
