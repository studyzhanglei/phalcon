<?php

namespace App\Providers\Lib;

use Phalcon\Di\FactoryDefault as Di;
use Phalcon\Logger;

class Log extends Logger
{
    /**
     * @var Di
     */
    private $di;

    public function __construct(string $name, array $adapters = array(), Di $di)
    {
        $this->di    = $di;
        parent::__construct($name, $adapters);
    }

    public function error($message, array $context = array()): void
    {
        /**
         * 接入sentry
         */

        parent::error($message, (array) $context);
    }

    public function critical($message, array $context = array()): void
    {
        /**
         * 接入sentry
         */


        /**
         * 钉钉报警
         */
        $this->di->getShared('helper')->noticeDDing(sprintf('%s params：%s', $message, var_export($context, true)), 'all');

        parent::critical($message, (array) $context);
    }
}