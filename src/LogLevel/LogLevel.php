<?php

declare(strict_types=1);

namespace flotzilla\Logger\LogLevel;

use \Psr\Log\LogLevel as PsrLogLevel;

class LogLevel extends PsrLogLevel
{
    public const LOG_LEVELS = [
        self::EMERGENCY,
        self::ALERT,
        self::CRITICAL,
        self::ERROR,
        self::WARNING,
        self::NOTICE,
        self::INFO,
        self::DEBUG
    ];

    public const LOG_LEVELS_INT = [
        self::EMERGENCY => 0,
        self::ALERT => 1,
        self::CRITICAL => 2,
        self::ERROR => 3,
        self::WARNING => 4,
        self::NOTICE => 5,
        self::INFO => 6,
        self::DEBUG => 7,
    ];
}