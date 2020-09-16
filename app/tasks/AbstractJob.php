<?php declare(strict_types=1);

namespace App\Tasks;

use App\Providers\lib\Amqp;
use App\Providers\lib\Helper;
use App\Tasks\Exception\TaskException;
use Exception;

abstract class AbstractJob extends  \Phalcon\Cli\Task
{
    /**
     * @return \Redis;
     * @author : zhanglei <zhanglei@dalongyun.com>
     */
    public function getRedis()
    {
        return $this->getDI()->getShared('redis');
    }

    /**
     * @return Logger;
     * @author : zhanglei <zhanglei@dalongyun.com>
     */
    public function getLog()
    {
        return $this->getDI()->get('log', [static::class]);
    }

    /**
     * @return Amqp;
     * @author : zhanglei <zhanglei@dalongyun.com>
     */
    public function getAmqp()
    {
        return $this->getDI()->getShared('amqp');
    }

    /**
     * @return Helper;
     * @author : zhanglei <zhanglei@dalongyun.com>
     */
    public function getHelper()
    {
        return $this->getDI()->getShared('helper');
    }
}