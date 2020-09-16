<?php declare(strict_types=1);

namespace App\Providers;

use Phalcon\Di\ServiceProviderInterface;
use Phalcon\Di\DiInterface;
use Phalcon\Mvc\View;

class ViewProvider implements ServiceProviderInterface
{
    public function register(DiInterface $di): void
    {
        $di->setShared('view', function () {
            $view = new View();
            return $view->disable();
        });
    }
}