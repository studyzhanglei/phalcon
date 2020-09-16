<?php declare(strict_types=1);
namespace App\Tasks;

use App\Models\A;

class TestTask extends AbstractJob
{
    public function runAction()
    {
        $logSer =  $this->getLog();
        while (true) {
            $logSer->info('哈哈哈哈');
            sleep(60);
        }
    }
}
