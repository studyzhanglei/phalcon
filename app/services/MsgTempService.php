<?php

namespace  App\Services;

use App\Models\Dao\MsgTempDao;
use App\Struct\MsgTempStruct;
use Phalcon\Di\Injectable;

class MsgTempService extends BaseService
{
    public static function getContent(MsgTempStruct $msg):string
    {
        $code   = $msg->getCode();
        $args   = $msg->getArgs();

        $msgTempRes     = self::getMsgTmp($code);

        if (empty($msgTempRes)) {
            self::getDI()->getShared('log')->error(sprintf('未配置文案模板 code：%s', $code));
            return '系统异常，请稍后尝试';
        }

        $content    = $msgTempRes['content'];
        if (empty($args)) {
            return $content;
        }

        foreach ($args as $arg) {
            $content    = preg_replace('/{\?}/', $arg, $content, 1);
        }

        return $content;
    }

    public static function getMsgTmp($code)
    {
        /**
         * @var \Redis
         */
        $redis      = self::getDI()->getShared('redis');
        $cacheKey   = sprintf('%s:%s', 'msg_temp', $code);
        $result     = $redis->get($cacheKey);

        if (FALSE === $result) {
            $result  = MsgTempDao::findOneByCode($code);
            $redis->setex($cacheKey, 300, json_encode($result));

            return $result;
        }

        return json_decode($result, true);
    }
}