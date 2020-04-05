<?php

declare(strict_types=1);

namespace flotzilla\Logger\Handler;

use flotzilla\Logger\LogLevel\LogLevel;

interface HandlerInterface
{
    /**
     * @param string $message
     * @param array $context
     * @param string $level
     * @param string $date
     * @return bool
     */
    public function handle(
        string $message = '',
        array $context = [],
        string $level = LogLevel::DEBUG,
        string $date = ''
    ): bool;
}
