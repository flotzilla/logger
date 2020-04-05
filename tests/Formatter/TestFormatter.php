<?php


namespace flotzilla\Logger\Test\Formatter;


use flotzilla\Logger\Formatter\FormatterInterface;
use flotzilla\Logger\LogLevel\LogLevel;

class TestFormatter implements FormatterInterface
{

    public function format(string $message = '',
                           array $context = [],
                           string $level = LogLevel::DEBUG,
                           string $date = ''
    ): string
    {
        return $message . ' | ' . $level . ' | ' . $date;
    }
}