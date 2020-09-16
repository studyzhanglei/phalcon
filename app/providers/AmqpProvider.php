<?php
declare(strict_types=1);

namespace App\Providers;

use Phalcon\Di\DiInterface;
use Phalcon\Di\ServiceProviderInterface;
use App\Providers\Lib\Amqp;

class AmqpProvider implements ServiceProviderInterface
{
    public function register(DiInterface $di): void
    {
        $di->setShared('amqp', function () use ($di) {
            $httpClient     = new Amqp($di);

            return $httpClient;
        });
    }
}
