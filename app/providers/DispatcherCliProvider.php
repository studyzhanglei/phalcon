<?php declare(strict_types=1);

namespace App\Providers;

use Phalcon\Di\ServiceProviderInterface;
use Phalcon\Di\DiInterface;
use App\Plugins\AuthPlugin;
use App\Plugins\NotFoundPlugin;
use Phalcon\Events\Manager as EventsManager;
use Phalcon\Cli\Dispatcher;

class DispatcherCliProvider implements ServiceProviderInterface
{
    public function register(DiInterface $di): void
    {
        $di->setShared('dispatcher', function () {
            $dispatcher = new Dispatcher();
            $dispatcher->setDefaultNamespace('App\\Tasks');
            return $dispatcher;

        });
    }
}