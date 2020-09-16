<?php
declare(strict_types=1);

namespace App\Providers;

use Phalcon\Di\DiInterface;
use Phalcon\Di\ServiceProviderInterface;
use App\Providers\Lib\Helper;

class HelperProvider implements ServiceProviderInterface
{
    public function register(DiInterface $di): void
    {
        $di->setShared('helper', function () use ($di) {
            $httpClient     = new Helper($di);

            return $httpClient;
        });
    }
}
