<?php

declare(strict_types=1);

namespace flotzilla\Logger\Exception;

use Throwable;

class InvalidChannelNameException extends \Exception
{
    /** @var string $message */
    protected $message = 'Channel name is invalid';

    /**
     * InvalidLogLevelException constructor.
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct($message = '', $code = 0, Throwable $previous = null)
    {
        $message = $message ?: $this->message;
        parent::__construct($message, $code, $previous);
    }
}
