<?php

declare(strict_types=1);

namespace flotzilla\Logger\Formatter;

use flotzilla\Logger\LogLevel\LogLevel;

interface FormatterInterface
{
    /**
     * Format log message and return string
     *
     * @param string $message
     * @param string $level
     * @param string $date
     * @param array $context
     * @return string
     */
    public function format(
        string $message = '',
        string $level = LogLevel::DEBUG,
        string $date = '',
        array $context = []
    ): string;
}
