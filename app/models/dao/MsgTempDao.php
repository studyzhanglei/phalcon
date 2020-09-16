<?php

namespace  App\Models\Dao;

use App\Models\MsgTemp;

class MsgTempDao extends MsgTemp
{
    public static function findOneByCode(string $code, $columns = "*")
    {
        return (array) parent::findFirst(
            [
                "columns"       => $columns,
                "conditions"    => 'code = :code: and is_del = :is_del:',
                'bind'          => [
                    'code'          => $code,
                    'is_del'        => 0,
                ],
            ]
        );
    }
}