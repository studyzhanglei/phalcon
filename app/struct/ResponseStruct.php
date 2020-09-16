<?php

namespace App\Struct;

class ResponseStruct
{
    /**
     * @var int
     */
    private $code = 200;

    /**
     * @var MsgTempStruct
     */
    private $msg;

    /**
     * @var array | object
     */
    private $data = [];

    /**
     * @return array|object
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @return self
     * @param array|object $data
     */
    public function setData($data): self
    {
        $this->data = $data;
        return $this;
    }

    /**
     * @return MsgTempStruct
     */
    public function getMsg(): MsgTempStruct
    {
        return $this->msg;
    }

    /**
     * @return self
     * @param MsgTempStruct $msg
     */
    public function setMsg(MsgTempStruct $msg): self
    {
        $this->msg = $msg;
        return $this;
    }

    /**
     * @return int
     */
    public function getCode(): int
    {
        return $this->code;
    }

    /**
     * @return self
     * @param int $code
     */
    public function setCode(int $code): self
    {
        $this->code = $code;
        return $this;
    }
}