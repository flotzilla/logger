<?php

declare(strict_types=1);

namespace flotzilla\Logger;

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
}