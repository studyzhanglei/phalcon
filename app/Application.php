<?php
namespace  App;

use App\Exception\BaseException;
use App\Services\MsgTempService;
use App\Struct\ResponseStruct;
use Cassandra\Uuid;
use Phalcon\Events\Event;
use Phalcon\Events\Manager as EventsManager;
use Phalcon\Http\Response;
use App\Plugins\AuthPlugin;

class Application extends \Phalcon\Mvc\Application
{
    public function beforeSendResponse(Event $event, \Phalcon\Mvc\Application $app, Response $response)
    {
        $content = $this->dispatcher->getReturnedValue();

        if ($content instanceof Response) {
            $content    = json_decode($response->getContent(), true);
        }

        if ($content instanceof \Phalcon\Mvc\Model) {
            $content =  $content->toArray();
        }

        if (NULL === $content || FALSE === $content) {
            $content    = (Object) [];
        }

        if (is_array($content)) {
            if (!isset($content['code'])) {
                $response->setJsonContent(
                    [
                        'code'  => 200,
                        'msg'   => 'success',
                        'data'  => $content,
                    ]
                );
            } else {
                $response->setJsonContent($content);
            }
        }

        if ($content instanceof ResponseStruct) {
            $msg            = $content->getMsg();
            $responseData   = [
                'code'          => $content->getCode(),
                'msg'           => MsgTempService::getContent($msg),
                'data'          => $content->getData(),
            ];

            $response->setJsonContent($responseData);
        }

        if ((Object) [] == $content) {
            $response->setJsonContent(
                [
                    'code'  => 200,
                    'msg'   => 'success',
                    'data'  => (Object) $content,
                ]
            );
        }



        $this->getDI()->get('log')->info(sprintf('response：%s', $response->getContent()));
        return $event->isStopped();
    }

    public function __construct($container = null)
    {
        parent::__construct($container);
    }
}