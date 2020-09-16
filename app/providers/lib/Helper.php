<?php declare(strict_types=1);
namespace  App\Providers\Lib;

use Phalcon\Di\FactoryDefault as Di;
use Ramsey\Uuid\Uuid;

class Helper
{
    /**
     * @var Di
     */
    private $di;

    public function __construct(Di $di)
    {
        $this->di    = $di;
    }

    /**
     * 系统内部钉钉报警
     * @param $msg
     * @param array $at
     * @return bool
     * @author : zhanglei <zhanglei@dalongyun.com>
     */
    public function noticeDDing($msg, $at = [])
    {
        $queueName  = $this->getQueueName('dingtalk');
        $msgData    = [
            'content'   => $msg,
            'at'        => $at,
        ];

        $this->di->getShared('amqp')->sendMsg($queueName, json_encode($msgData));
        return true;
    }

    /**
     * 钉钉报警实现（脚本调用）
     * @param $msg
     * @param array $at
     * @return bool
     * @author : zhanglei <zhanglei@dalongyun.com>
     */
    public function doNoticeDDing($msg, $at = [])
    {
        $config     = $this->di->getShared('config')->path('aliyun.dingding');
        $token      = $config['token'];
        $secret     = $config['secret'];
        $baseUrl    = $config['base_url'];

        // 获取微秒数时间戳
        $Temptime = explode(' ', microtime());
        // 转换成毫秒数时间戳
        $msectime = (float)sprintf('%.0f', (floatval($Temptime[0]) + floatval($Temptime[1])) * 1000);


        // 拼装成待加密字符串
        // 格式：毫秒数+"\n"+密钥
        $stringToSign=$msectime."\n".$secret;
        // 进行加密操作 并输出二进制数据
        $sign=hash_hmac('sha256', $stringToSign,$secret,true);
        // 加密后进行base64编码 以及url编码
        $sign=urlencode(base64_encode($sign));

        $queryData   = [
            'access_token'  => $token,
            'timestamp'     => $msectime,
            'sign'          => $sign,
        ];

        $url         = $baseUrl . '?' . http_build_query($queryData);

        $postData    = [
            'msgtype'   => 'text',
            'text'      => [
                'content'   => $msg
            ]
        ];

        if (!empty($at)) {
            if (is_array($at)) {
                $at     = array_map(function ($v) use($config) {
                    return $config['at'][$v] ?? $config['at']['zhanglei'];
                }, $at);

                $postData['at'] = [
                    'atMobiles'     => array_unique($at),
                    'isAtAll'       => false,
                ];
            } else if ('all' == $at) {
                $postData['at'] = [
                    'isAtAll'       => true,
                ];
            }

        }

        /**
         * @var \App\Providers\Lib\HttpClient
         */
        $httpClient  = $this->di->getShared('httpClient');

        $option      = [
            'headers'       => [
                'Content-Type'  => 'application/json',
            ]
        ];

        $res         = $httpClient->post($url, $postData, $option, true);

        if (isset($res['errcode']) && $res['errcode'] === 0) {
            return true;
        }

        return false;
    }

    /**
     * @param $queue
     * @return string
     * @author : zhanglei <zhanglei@dalongyun.com>
     */
    public function getQueueName(string $queue): string
    {
        $config     = $this->di->getShared('config');

        if (empty($config->path('aliyun.queue.prefix'))) {
            return $config->path('aliyun.queue.list')[$queue];
        }

        return sprintf('%s-%s', $config->path('aliyun.queue.prefix'), $config->path('aliyun.queue.list')[$queue]);
    }

    /**
     * @param string $prefix
     * @return string
     * @author : zhanglei <zhanglei@dalongyun.com>
     */
    public function getTaskId($prefix = ''): string
    {
        if (empty($prefix)) {
            return str_replace('-', '', Uuid::uuid1()->toString());
        }

        return $prefix . str_replace('-', '', Uuid::uuid1()->toString());
    }
}