<?php

$router = $di->getRouter();

$router->setDefaults(
    [
        'action'    => 'run',
        'task'      => 'main',
    ]
);

$router->add("/:task/:action/params", array(
    'namespace' => 'App\\Tasks',
    'task'      => 1,
    'action'    => 2,
    'params'    => 3
));
