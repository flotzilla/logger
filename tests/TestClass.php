<?php


namespace flotzilla\Logger\Test;


class TestClass
{

    protected $message = 'some test string from test class';

    public function __toString()
    {
        return $this->message;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }
}