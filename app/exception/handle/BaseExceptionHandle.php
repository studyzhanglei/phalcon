<?php
namespace App\Exception\Handle;

use App\Services\MsgTempService;
use App\Struct\MsgTempStruct;
use Exception;
use Phalcon\Di;
use Phalcon\Mvc\Dispatcher\Exception as DispatcherException;
use App\Exception\BaseException;

class BaseExceptionHandle
{
    private $di;

    public function __construct(Di $di)
    {
        $this->setDi($di);
    }

    public function setDi(Di $di)
    {
        $this->di   = $di;
        return $this;
    }

    public function getDi()
    {
        return $this->di;
    }

    public function handle(Exception $exception)
    {
        $response   = $this->getDi()->getResponse();

        if ($exception instanceof DispatcherException) {
            switch ($exception->getCode()) {
                case DispatcherException::EXCEPTION_HANDLER_NOT_FOUND:
                case DispatcherException::EXCEPTION_ACTION_NOT_FOUND:
                    $response->setJsonContent(
                        [
                            'code'      => BaseException::ROUTE_NOT_FOUND,
                            'msg'       => 'route not found',
                            'data'      => (Object) [],
                        ]
                    );
                    return $response;
            }
        }

        if ($exception instanceof BaseException) {
            return $this->getReason($exception, $response);
        }

        $msgTempStruct  = new MsgTempStruct();
        $msgTempStruct->setCode(BaseException::$reasons[BaseException::SYSTEM_ERR]);

        $data = [
            'code'      => BaseException::PARAMS_ERR,
            'msg'       => MsgTempService::getContent($msgTempStruct),
            'data'      => (Object) [],
        ];

        if (getenv('APP_ENV') != 'prod') {
            $data['code']   = $exception->getCode();
            $data['msg']    = $exception->getMessage();
            $data['trace']  = $exception->getTraceAsString();
        }

        $message     = 'msg：{msg} trace：{trace}';
        $content     = [
            'msg'       => $exception->getMessage(),
            'trace'     => json_encode($exception->getTrace()),
        ];

        $this->getDI()->get('log')->error($message, $content);

        $response->setJsonContent($data);

        return $response;
    }

    public function getReason($exception, $response)
    {
        $data   = [
            'code'      => $exception->getCode(),
            'msg'       => MsgTempService::getContent($exception->getMessage()),
            'data'      => (Object) [],
        ];

        if (getenv('APP_ENV') != 'prod') {
            $data['trace']  = $exception->getTraceAsString();
        }

        $response->setStatusCode($exception->getStatusCode($exception->getCode()));

        return $response->setJsonContent($data);
    }
}