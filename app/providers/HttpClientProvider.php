<?php
declare(strict_types=1);

namespace App\Providers;

use Phalcon\Di\DiInterface;
use Phalcon\Di\ServiceProviderInterface;
use App\Providers\Lib\HttpClient;

class HttpClientProvider implements ServiceProviderInterface
{
    public function register(DiInterface $di): void
    {
        $di->setShared('httpClient', function () use ($di) {
            $httpClient     = new HttpClient($di);

            return $httpClient;
        });
    }
}
