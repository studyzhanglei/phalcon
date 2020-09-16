<?php declare(strict_types=1);

namespace App\Providers;

use Phalcon\Di\ServiceProviderInterface;
use Phalcon\Di\DiInterface;
use Phalcon\Events\Manager as EventsManager;

class DbProvider implements ServiceProviderInterface
{
    public function register(DiInterface $di): void
    {
        $di->setShared('db', function () {
            $config = $this->getConfig();

            $class = 'Phalcon\Db\Adapter\Pdo\\' . $config->database->adapter;
            $params = [
                'host'     => $config->database->host,
                'username' => $config->database->username,
                'password' => $config->database->password,
                'dbname'   => $config->database->dbname,
                'charset'  => $config->database->charset
            ];

            if ($config->database->adapter == 'Postgresql') {
                unset($params['charset']);
            }

            $connection = new $class($params);


            $profiler = $this->getProfiler();
            $eventsManager = new EventsManager();
            $di       = $this;
            //监听所有的db事件
            $eventsManager->attach('db', function($event, $connection) use ($profiler, $di) {
                //一条语句查询之前事件，profiler开始记录sql语句
                if ($event->getType() == 'beforeQuery') {
                    $profiler->startProfile($connection->getSQLStatement());
                }

                //一条语句查询结束，结束本次记录，记录结果会保存在profiler对象中
                if ($event->getType() == 'afterQuery') {
                    $profiler->stopProfile();

                    if ('local' == getenv('APP_ENV')) {
                        $di->getShared('log')->info($connection->getSQLStatement());
                    }
                }
            });

            $connection->setEventsManager($eventsManager);

            return $connection;
        });
    }
}