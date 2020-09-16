<?php declare(strict_types=1);

namespace App\Providers;

use Phalcon\Di\ServiceProviderInterface;
use Phalcon\Di\DiInterface;

class ProfilerProvider implements ServiceProviderInterface
{
    public function register(DiInterface $di): void
    {
        $di->setShared('profiler', function () {
            return new \Phalcon\Db\Profiler();
        });
    }
}