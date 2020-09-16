<?php

namespace App\Struct;

class MsgTempStruct
{
    /**
     * @var string
     */
    private $code    = '';

    /**
     * @var array
     */
    private $args   = [];

    /**
     * @return array
     */
    public function getArgs(): array
    {
        return $this->args;
    }

    /**
     * @return self
     * @param array $args
     */
    public function setArgs(array $args): self
    {
        $this->args = $args;
        return $this;
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @return self
     * @param string $code
     */
    public function setCode(string $code): self
    {
        $this->code = $code;
        return $this;
    }

}