<?php

declare(strict_types=1);

namespace flotzilla\Logger\Handler;

use flotzilla\Logger\LogLevel\LogLevel;

interface HandlerInterface
{
    /**
     * @param string $message
     * @param string $level
     * @param string $date
     * @param array $context
     * @return bool
     */
    public function handle(
        string $message = '',
        string $level = LogLevel::DEBUG,
        string $date = '',
        array $context = []
    ): bool;
}
