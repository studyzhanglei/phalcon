<?php
namespace App\Providers\Lib;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Wire\AMQPTable;
use Ramsey\Uuid\Uuid;
use Phalcon\Di\DiInterface as Di;

/**
 * 阿里云 AMQP操作类
 * 如果这里面的方法无法满足需求 请使用(new Amqp)->getChannel() 去实现
 * @author zhanglei <study.zhanglei@gmail.com>
 * Class Amqp
 */
class Amqp
{
    /**
     * @var array
     */
    private $config = array();

    /**
     * @var Di
     */
    private $di;

    /**
     * Amqp constructor.
     */
    public function __construct($di)
    {
        $this->di = $di;
        $this->connect();
    }

    /**
     * @var \PhpAmqpLib\Channel\AMQPChannel
     */
    private $channel;

    /**
     * @var AMQPStreamConnection
     */
    private $connection;

    /**
     * @author : zhanglei <zhanglei@dalongyun.com>
     */
    public function connect()
    {
        $config     = $this->getConfig();

        $connectionUtil = new ConnectionUtil(
            $config['host'],
            $config['port'],
            $config['virtualHost'],
            $config['accessKey'],
            $config['accessSecret'],
            $config['resourceOwnerId']
        );

        $this->connection = $connectionUtil->getConnection();
    }

    /**
     * 阿里云amqp服务重新连接
     * @author : zhanglei <zhanglei@dalongyun.com>
     */
    public function resetConnect()
    {
        $config     = $this->getConfig();
        $connectionUtil = new ConnectionUtil(
            $config['host'],
            $config['port'],
            $config['virtualHost'],
            $config['accessKey'],
            $config['accessSecret'],
            $config['resourceOwnerId']
        );

        $this->connection = $connectionUtil->getConnection();
        /**
         * 重置channel
         */
        $this->channel    = null;
        return $this;
    }

    /**
     * 保证获取的channel是同一个
     * @return \PhpAmqpLib\Channel\AMQPChannel
     * @author : zhanglei <zhanglei@dalongyun.com>
     */
    public function getChannel()
    {
        if (!$this->channel) {
            $this->channel  = $this->connection->channel();
        }

        return $this->channel;
    }

    /**
     * send message
     * if $msg instanceof AMQPMessage, $delay is invalid
     * @param $msg
     * @param $queue
     * @param int $delay
     * @return mixed
     * @author : zhanglei <zhanglei@dalongyun.com>
     */
    public function sendMsg($queue, $msg, $delay = 0)
    {
        if (!($msg instanceof AMQPMessage)) {
            $messageId  = Uuid::uuid1();
            $prop       = [
                'message_id'    => $messageId,
                'delivery_mode' => 2,
            ];

            if (0 != $delay) {
                $prop['application_headers'] = new AMQPTable(["delay" => $delay]);
            }
            $msg    = new AMQPMessage($msg, $prop);
        }

        $this->di->getShared('log')->info(
            sprintf(
                'send queue msg queue：%s msg：%s prop：%s', $queue, $msg->getBody(), json_encode($msg->get_properties())
            )
        );

        try {
            $this->getChannel()->basic_publish($msg, '', $queue);
        } catch (\Exception $e) {
            $this->resetConnect()->getChannel()->basic_publish($msg, '', $queue);
        }

        return true;
    }

    /**
     * 获取配置
     *
     * @return array|mixed
     * @author : zhanglei <zhanglei@dalongyun.com>
     */
    public function getConfig()
    {
        if (empty($this->config)) {
            $this->config   = $this->di->get('config')->path('aliyun.account');
        }

        return $this->config;
    }

    /**
     * @desc  创建交换机
     * @param $e_name
     * @param string $type
     * @param bool $passive
     * @param bool $durable
     * @param bool $auto_delete
     * @param bool $internal
     * @param bool $nowait
     * @param array $arguments
     * @param null $ticket
     */
    public function declareExchange($e_name,$type='direct',$passive = false,$durable = false,$auto_delete = true,$internal = false,$nowait = false,$arguments = array(),$ticket = null)
    {
        $this->getChannel()->exchange_declare($e_name,$type,$passive,$durable,$auto_delete,$internal,$nowait,$arguments,$ticket);
    }

    /**
     * @desc 创建队列
     * @param string $queue
     * @param bool $passive
     * @param bool $durable
     * @param bool $exclusive
     * @param bool $auto_delete
     * @param bool $nowait
     * @param array $arguments
     * @param null $ticket
     */
    public function declareQueue($queue = '',$passive = false,$durable = false,$exclusive = false,$auto_delete = true,$nowait = false,$arguments = array(),$ticket = null)
    {
        $this->getChannel()->queue_declare($queue, $passive, $durable, $exclusive, $auto_delete,$nowait,$arguments,$ticket);
    }

    /**
     * @throws Exception
     */
    public function __destruct()
    {
        try {
            if ($this->connection) {
                if ($this->getChannel()) {
                    $this->getChannel()->close();
                }

                $this->connection->close();
            }
        } catch (\Exception $e) {
            /**
             * 关闭的时候可能会报连接异常
             */
        }
    }


    /**
     * @param $queue
     * @param callable $callback
     * @return \PhpAmqpLib\Channel\AMQPChannel
     * @author : zhanglei <zhanglei@dalongyun.com>
     */
    public function bindConsume($queue, callable $callback)
    {
        $channel    = $this->getChannel();
        $channel->queue_declare($queue, false, false, false, false);
        $channel->basic_consume($queue, '', false, false, false, false, $callback);

//        $this->channel = $channel;
        return $channel;
    }
}