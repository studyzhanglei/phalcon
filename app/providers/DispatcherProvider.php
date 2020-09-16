<?php declare(strict_types=1);

namespace App\Providers;

use Phalcon\Di\ServiceProviderInterface;
use Phalcon\Di\DiInterface;
use App\Plugins\AuthPlugin;
use App\Plugins\NotFoundPlugin;
use Phalcon\Events\Manager as EventsManager;
use Phalcon\Mvc\Dispatcher;

class DispatcherProvider implements ServiceProviderInterface
{
    public function register(DiInterface $di): void
    {
        $di->setShared('dispatcher', function () {
            $eventsManager = new EventsManager();
            /**
             * Handle exceptions and not-found exceptions using NotFoundPlugin
             */
            $eventsManager->attach('dispatch:beforeExecuteRoute', new AuthPlugin, 150);

            /**
             * Handle exceptions and not-found exceptions using NotFoundPlugin
             */
            $eventsManager->attach('dispatch:beforeException', new NotFoundPlugin, 200);

            $dispatcher = new Dispatcher();
            $dispatcher->setEventsManager($eventsManager);

            return $dispatcher;
        });
    }
}