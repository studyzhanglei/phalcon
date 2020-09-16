<?php declare(strict_types=1);

namespace App\Tasks;

use App\Providers\lib\Amqp;
use App\Providers\lib\Helper;
use App\Tasks\Exception\TaskException;
use Exception;
use PhpAmqpLib\Exception\AMQPTimeoutException;
use PhpAmqpLib\Exception\AMQPConnectionClosedException;
use PhpAmqpLib\Exception\AMQPChannelClosedException;
use PhpAmqpLib\Exception\AMQPIOException;

abstract class AbstractQueue extends  \Phalcon\Cli\Task
{
    /**
     * @var
     */
    public static $msg;

    /**
     * @return mixed
     * @param $msg
     * @author : zhanglei <zhanglei@dalongyun.com>
     */
    abstract protected function doHandle($msg);

    /**
     * @param $msg
     * @return mixed
     * @author : zhanglei <zhanglei@dalongyun.com>
     */
    public function handle($msg)
    {
        try {
            $messageId  = $msg->get('message_id');
        }
        catch (\OutOfBoundsException $e) {
            $messageId  = '';
        }

        $this->getRedis()->sadd('s', $messageId);

        $logStr     = sprintf('message_id：%s queue-msg：%s', $messageId, $msg->body);
        $this->getLog()->info($logStr);

        /**
         * 处理消息
         */
        $this->doHandle($msg);

        $this->ack();
    }

    public function runAction()
    {
        while (true) {
            try {
                $amqp = $this->getAmqp();
                $callback = function ($msg) {
                    static::$msg = $msg;
                    return $this->handle($msg);
                };

                $queueName = $this->getHelper()->getQueueName((string) static::$queue);
                $channel = $amqp->bindConsume($queueName, $callback);

                while (count($channel->callbacks)) {
                    $channel->wait();
                    usleep(10);
                }
            }  catch (TaskException $e) {
                $this->ack();

                $errorStr   = sprintf('消息脚本处理异常  queue-msg: %s error-msg：%s',
                    static::$msg->body,
                    $e->getMessage()
                );

                $this->getLog()->notice($errorStr);

            } catch (Exception $e) {
                var_dump(date('Y-m-d H:i:s'));
                var_dump(get_class($e));

                if (
                    $e instanceof AMQPIOException
                    || $e instanceof AMQPTimeoutException
                    || $e instanceof AMQPChannelClosedException
                    || $e instanceof AMQPConnectionClosedException
                ) {
                    $this->getAmqp()->resetConnect();
                    continue;
                }

                $this->ack();

                $errorStr   = sprintf('消息脚本处理错误 queue-msg: %s error-msg：%s',
                    static::$msg->body,
                    $e->getMessage()
                );

                /**
                 * 防止死循环
                 */
                if ('dingtalk'  != static::$queue) {
                    $this->getHelper()->noticeDDing($errorStr);
                }

                $errorStr   = sprintf('%s error-trace：%s', $errorStr, json_encode($e->getTraceAsString()));
                $this->getLog()->error($errorStr);
            }
        }
    }

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
        return $this->getDI()->getShared('log', [static::$queue]);
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

    public function ack()
    {
        static::$msg->delivery_info['channel']->basic_ack(static::$msg->delivery_info['delivery_tag']);
    }
}