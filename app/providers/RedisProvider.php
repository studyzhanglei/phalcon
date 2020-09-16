<?php declare(strict_types=1);

namespace App\Providers;

use Phalcon\Di\ServiceProviderInterface;
use Redis;
use Phalcon\Di\DiInterface;

class RedisProvider implements ServiceProviderInterface
{
    public function register(DiInterface $di): void
    {
        $di->setShared('redis', function () use ($di) {
            $config    = $di->getShared('config')->path('redis');
            $redis     = new Redis();

            $redis->connect($config['host'], $config['port']);

            if (!empty($config['password'])) {
                $redis->auth($config['password']);
            }

            if (!empty($config['db'])) {
                $redis->select($config['db']);
            }

            return $redis;
        });
    }
}