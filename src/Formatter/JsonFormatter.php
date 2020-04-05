<?php

declare(strict_types=1);

namespace flotzilla\Logger\Formatter;

use flotzilla\Logger\LogLevel\LogLevel;

class JsonFormatter implements FormatterInterface
{
    public function format(
        string $message = '',
        array $context = [],
        string $level = LogLevel::DEBUG,
        string $date = ''
    ) : string
    {
        // TODO implement more sophisticated approach
        return json_encode($message) . PHP_EOL;
    }
}
