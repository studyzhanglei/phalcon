<?php
declare(strict_types=1);

/**
 * This file is part of the Invo.
 *
 * (c) Phalcon Team <team@phalcon.io>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace App\Plugins;

use Exception;
use Phalcon\Di\Injectable;
use Phalcon\Events\Event;
use Phalcon\Mvc\Dispatcher as MvcDispatcher;
use Phalcon\Mvc\Dispatcher\Exception as DispatcherException;
use App\Exception\Handle\BaseExceptionHandle;

/**
 * NotFoundPlugin
 *
 * Handles not-found controller/actions
 */
class NotFoundPlugin extends Injectable
{
    /**
     * This action is executed before perform any action in the application
     *
     * @param Event $event
     * @param MvcDispatcher $dispatcher
     * @param Exception $exception
     * @return bool
     */
    public function beforeException(Event $event, MvcDispatcher $dispatcher, Exception $exception)
    {
        $return    = (new BaseExceptionHandle($this->getDI()))->handle($exception);

        $dispatcher->setReturnedValue($return);
        return false;
    }
}
