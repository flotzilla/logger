<?php

declare(strict_types=1);

namespace flotzilla\Logger\Exception;

class HandlerException extends LogException
{
    /** @var string $message */
    protected $message = 'Handler Exception';
}
