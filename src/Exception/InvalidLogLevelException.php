<?php


namespace flotzilla\Logger\Exception;


use Psr\Log\InvalidArgumentException;
use Throwable;

class InvalidLogLevelException extends InvalidArgumentException
{
    protected $message = "Invalid log level configuration";

    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        $message = $message ?: $this->message;
        parent::__construct($message, $code, $previous);
    }
}