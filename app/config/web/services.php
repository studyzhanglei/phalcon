<?php
declare(strict_types=1);

$di->register(new \App\Providers\ProfilerProvider());
$di->register(new \App\Providers\DbProvider());
$di->register(new \App\Providers\DispatcherProvider());
$di->register(new \App\Providers\ConfigProvider());
$di->register(new \App\Providers\ViewProvider());
$di->register(new \App\Providers\HttpClientProvider());
$di->register(new \App\Providers\AmqpProvider());
$di->register(new \App\Providers\RedisProvider());
$di->register(new \App\Providers\HelperProvider());
$di->register(new \App\Providers\LogProvider());
