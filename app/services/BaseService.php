<?php

namespace  App\Services;
use Phalcon\Di;

class BaseService
{
    public static function getDI()
    {
        return Di::getDefault();
    }
}