<?php declare(strict_types=1);
namespace App\Tasks;

use App\Models\A;
use Exception;

class MainTask extends AbstractQueue
{
    /**
     * @var string
     */
    public static $queue  = 'test';

    protected function doHandle($msg)
    {
        $msgData = $msg->body;
//        throw new Exception('章磊故意抛出的异常');
        var_dump($this->getRedis()->get('a'));
        var_dump(A::findFirst()->toArray());
        var_dump($msgData);
    }
}
