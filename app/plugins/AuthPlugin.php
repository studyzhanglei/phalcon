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
use Phalcon\Http\Response;
use Phalcon\Mvc\Dispatcher\Exception as DispatcherException;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Mvc\Application;
use Phalcon\Mvc\View;

/**
 * NotFoundPlugin
 *
 * Handles not-found controller/actions
 */
class AuthPlugin extends Injectable
{
    public function beforeExecuteRoute(Event $event, Dispatcher $dispatcher)
    {
        $this->getDI()->get('log')->info(
            sprintf('header：%s post：%s get：%s',
                json_encode($this->request->getHeaders()),
                json_encode($this->request->getPost()),
                json_encode($this->request->getQuery())
            )
        );

        return true;
    }
}
