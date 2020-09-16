<?php declare(strict_types=1);
namespace App\Tasks;

use Exception;
use App\Tasks\Exception\TaskException;

class DingtalkTask extends AbstractQueue
{
    /**
     * @var string
     */
    public static $queue  = 'dingtalk';


    protected function doHandle($msg)
    {
        $msgString  = $msg->body;
        $msgData    = json_decode($msgString, true);

        if (empty($msgData['content'])) {
            throw new TaskException(sprintf('params can not empty：%s', 'content'));
        }

        if (empty($msgData['at'])) {
            $this->getHelper()->doNoticeDDing($msgData['content']);
        } else {
            $this->getHelper()->doNoticeDDing($msgData['content'], $msgData['at']);
        }

        return;
    }
}
