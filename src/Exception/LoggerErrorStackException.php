<?php

declare(strict_types=1);

namespace flotzilla\Logger\Exception;

use Countable;
use Exception;
use Throwable;

class LoggerErrorStackException extends Exception implements Countable
{
    /** @var array $errorStack */
    protected $errorStack;

    protected $message = 'Invalid log level configuration';

    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        $message = $message ?: $this->message;
        parent::__construct($message, $code, $previous);
    }

    /**
     * @return mixed
     */
    public function getErrorStack(): array
    {
        return $this->errorStack;
    }

    /**
     * @param mixed $errorStack
     */
    public function setErrorStack($errorStack): void
    {
        $this->errorStack = $errorStack;
    }

    /**
     * @param Throwable $error
     */
    public function addError(Throwable $error): void
    {
        $this->errorStack[] = $error;
    }

    public function mergeErrors(array $errors): void
    {
        $this->errorStack = array_merge($this->errorStack, $errors);
    }

    /**
     * @inheritDoc
     */
    public function count()
    {
        return $this->errorStack;
    }
}
