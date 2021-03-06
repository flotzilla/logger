<?php

declare(strict_types=1);

namespace flotzilla\Logger\Exception;

use Exception;
use Throwable;

abstract class LogException extends Exception
{
    /** @var string $message */
    protected $message = 'Exception during logging';

    /** @var string $logMessage */
    protected $logMessage;

    /** @var array $logData */
    protected $logData = [];

    /** @var string $logLevel */
    protected $logLevel;

    /** @var string $logDate */
    protected $logDate;

    /**
     * LogException constructor.
     * @param string $message error message
     * @param string $logMessage
     * @param array $context
     * @param string $level
     * @param string $date
     * @param Throwable|null $previous
     */
    public function __construct(
        string $message = '',
        string $logMessage = '',
        array $context = [],
        string $level = '',
        string $date = '',
        Throwable $previous = null
    )
    {
        $message = $message ?: $this->message;
        $this->logMessage = $logMessage;
        $this->logData = $context;
        $this->logLevel = $level;
        $this->logDate = $date;
        parent::__construct($message, 0, $previous);
    }

    /**
     * @return mixed
     */
    public function getLogMessage()
    {
        return $this->logMessage;
    }

    /**
     * @return array
     */
    public function getLogData(): array
    {
        return $this->logData;
    }

    /**
     * @return mixed
     */
    public function getLogLevel()
    {
        return $this->logLevel;
    }

    /**
     * @return mixed
     */
    public function getLogDate()
    {
        return $this->logDate;
    }
}
