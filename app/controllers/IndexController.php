<?php
declare(strict_types=1);

use App\Exception\BaseException;
use Phalcon\Http\Response;
use Ramsey\Uuid\Uuid;

class IndexController extends ControllerBase
{

    public function indexAction()
    {
//        echo Uuid::uuid1()->toString();die;
        $return     = [
            'name'      => '章磊',
            'age'       => 20,
        ];
//        throw new BaseException(BaseException::PARAMS_ERR);
//        $response  = new Response();

//        var_dump($response->setStatusCode());

//        return (new Response())->setJsonContent($return);
        return (new Response())->setContent('dddd');
    }

}

