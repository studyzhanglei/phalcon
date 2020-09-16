<?php
/**
 * Base exception.
 */

namespace App\Exception;

use Exception;
use App\Struct\MsgTempStruct;

class BaseException extends Exception
{
    const SYSTEM_ERR        = 500;
    const AUTH_FAILED       = 401;
    const PARAMS_ERR        = 501;
    const ROUTE_NOT_FOUND   = 404;

    public static $reasons = [
        self::SYSTEM_ERR        => 'system_error',
        self::AUTH_FAILED       => 'auth_fail',
        self::PARAMS_ERR        => 'params_error',
        self::ROUTE_NOT_FOUND   => 'route_not_found',
    ];

    public static $statusCode = [
        self::ROUTE_NOT_FOUND => 404,
        self::SYSTEM_ERR      => 500,
        self::AUTH_FAILED     => 401,
    ];

    public function __construct($code = null, $message = null)
    {
        $this->code    = $code;
        $this->message = $message ? $message : self::getReason($code);
    }

    public static function getReason($code)
    {
//        return isset(static::$reasons[$code]) ? static::$reasons[$code] : self::$reasons[self::SYSTEM_ERR];
        $reasonCode     = isset(static::$reasons[$code]) ? static::$reasons[$code] : self::$reasons[self::SYSTEM_ERR];
        $msgTempStruct  = new MsgTempStruct();
        $msgTempStruct->setCode($reasonCode);

        return $msgTempStruct;
    }

    public static function getStatusCode($code)
    {
        return isset(static::$statusCode[$code]) ? static::$statusCode[$code] : 200;
    }
}