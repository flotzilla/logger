<?php

declare(strict_types=1);

namespace flotzilla\Logger\Handler;

use flotzilla\Logger\Exception\FormatterException;
use flotzilla\Logger\Exception\HandlerException;
use flotzilla\Logger\LogLevel\LogLevel;

interface HandlerInterface
{
    /**
     * @param string $message
     * @param array $context
     * @param string $level
     * @param string $date
     * @return bool
     *
     * @throws HandlerException
     * @throws FormatterException
     */
    public function handle(
        string $message = '',
        array $context = [],
        string $level = LogLevel::DEBUG,
        string $date = ''
    ): bool;
}
