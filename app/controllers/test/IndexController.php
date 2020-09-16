<?php

namespace App\Controllers\Test;
use App\Controllers\ControllerBase;
use App\Exception\BaseException;
use App\Models\A;
use App\Struct\MsgTempStruct;
use App\Struct\ResponseStruct;
use Phalcon\Mvc\Controller;
use Ramsey\Uuid\Uuid;
use Phalcon\Http\Response;

class IndexController extends ControllerBase
{
    public function indexAction($params = null)
    {
        var_dump($params);
        var_dump($this->dispatcher->getParam(0));

    }

    public function aAction()
    {
        throw  new BaseException(BaseException::ROUTE_NOT_FOUND);
    }

    public function msgAction()
    {
        $msgStruct  = new MsgTempStruct();
        $msgStruct->setCode('abcd');

        $responseStruct = new ResponseStruct();
        $responseStruct->setMsg($msgStruct);

        return $responseStruct;
    }

    public function msg2Action()
    {
        $msgStruct  = new MsgTempStruct();
        $msgStruct->setCode('b');
        $msgStruct->setArgs([1, 100]);

        $responseStruct = new ResponseStruct();
        $responseStruct->setMsg($msgStruct);

        return $responseStruct;
    }

    public function showAllAction(){
        $res = $this->db->book->find();
        foreach ($res as $doc){
            echo $doc->title."|".$doc->nb_store."<br/>";
        }

    }

    public function searchAction()
    {
//        var_dump(11111);die;
//        $this->getDI()->getShared('helper')->noticeDDing('测试脚本是否正常', ['zhanglei']);
//        $redis  = $this->getDI()->getShared('redis');
//        var_dump($redis);die;

//        var_dump($this->getDI()->getShared('helper')->getTaskId());die;
//        var_dump(UUid::uuid1()->toString());
//die;
//        var_dump($redis->get('a'));die;
//        die;
//        var_dump($this->getDI()->getShared('redis')->get('a'));die;
        for ($i = 0; $i < 1; ++$i) {
            $uuid = Uuid::uuid1()->toString();
//            die;
            $queueName = $this->getDI()->getShared('helper')->getQueueName('test');
//            var_dump($queueName);
//            echo $queueName;
            $res = $this->getAmqp()->sendMsg($queueName, '9999999');
//            $queueName = $this->getDI()->getShared('helper')->getQueueName('dingtalk');
//            $res = $this->getDI()->getShared('amqp')->sendMsg($queueName, 'zzzzzzz');
//            die;
//            var_dump($res);
//            die;
//            $this->getDI()->getShared('log')->info($uuid);
//            die;
        }


//        return ($this->getDI()->getShared('httpClient')->post('http://localhost/qq.php'));
//        throw new \Exception('eeeeee');

//        $this->getDI()->get('log')->error('ddddd');
//        $this->getDI()->get('log')->info('发反反复复');
//        $this->getDI()->get('log')->excludeAdapters(['error'])->error('溪秀');
//var_dump($this->request->getServer('X-Request-Id'));
//        var_dump($this->request->getHeader('X-Request-Id'));
        $params     = [
            'order'     => 'id desc',
        ];
//        echo 5666;die;
        $result     = A::findFirst(
            [
//                "conditions"     => "id = 1"
            ]
        );
//        echo 111;
//var_dump($result);die;
return $result;
//        die;

//        throw new \Exception('ddddd');
//        $query  = A::query();
//        $query->leftJoin('')
//        var_dump($result->getId());

//        var_dump($result->delete());
//        var_dump( A::count(
////            [
////                    'id' => '?0',
////                'bind' => [
////                    10
////                ],
////            ]
////               [
////                   'group' => 'id',
////                   'order' => 'id',
////               ]
//            [
//                'conditions' => 'id = 11'
//            ]
//        ));
//        echo $this->di->get('profiler')->getLastProfile()->getSQLStatement();
//        die;


//var_dump($result);die;

//            ->getQuery()->getSql();


//        var_dump($result);die;
        //        var_dump($result);
//        die;
        return (Object) [];
    }
}
