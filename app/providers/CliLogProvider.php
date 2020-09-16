<?php
declare(strict_types=1);

namespace App\Providers;

use App\Providers\Lib\Log;
use Phalcon\Di\DiInterface;
use Phalcon\Di\ServiceProviderInterface;
use Phalcon\Logger;
use Phalcon\Logger\Adapter\Stream;

class CliLogProvider implements ServiceProviderInterface
{
    public function register(DiInterface $di): void
    {
        $di->setShared('log', function (string $queue = '') use ($di) {
            $adapter1 = new Stream(BASE_PATH . sprintf('/runtime/logs/task_%s.log', date('Y-m-d')));

            $logger = new Log(
                'messages',
                [
                    'local'   => $adapter1,
                ],
                $di
            );

            $request  = new \Phalcon\Http\Request();
            $format   = new Logger\Formatter\Line('[%date%][%type%][' . $queue . '] %message%', 'Y-m-d H:i:s');
            $adapter1->setFormatter($format);
            return $logger;
        });
    }
}
