<?php

declare(strict_types=1);

namespace flotzilla\Logger\Exception;

class FormatterException extends LogException
{
    /** @var string $message */
    protected $message = 'Formatter Exception';
}
