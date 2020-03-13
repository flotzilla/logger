<?php

namespace flotzilla\Logger\Exception;

use Exception;
use Throwable;

class InvalidConfigurationException extends Exception
{
    protected $message = "Invalid configuration";

    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        $message = $message ?: $this->message;
        parent::__construct($message, $code, $previous);
    }
}
