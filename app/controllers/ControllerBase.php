<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Providers\Lib\Amqp;
use App\Providers\Lib\Helper;
use Phalcon\Events\Event;
use Phalcon\Http\Response;
use Phalcon\Logger;
use Phalcon\Mvc\Application;
use Phalcon\Mvc\Controller;

class ControllerBase extends Controller
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
        return $this->getDI()->getShared('log');
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
