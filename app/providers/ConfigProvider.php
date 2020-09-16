<?php declare(strict_types=1);

namespace App\Providers;

use Phalcon\Di\ServiceProviderInterface;
use Phalcon\Di\DiInterface;

class ConfigProvider implements ServiceProviderInterface
{
    public function register(DiInterface $di): void
    {
        $di->setShared('config', function () {
            return include APP_PATH . "/config/config.php";
        });
    }
}