<?php


namespace flotzilla\Logger\Test\Formatter;


use flotzilla\Logger\Formatter\FormatterInterface;
use flotzilla\Logger\LogLevel\LogLevel;

class TestFormatter implements FormatterInterface
{

    public function format(string $message = '',
                           string $level = LogLevel::DEBUG,
                           string $date = '',
                           array $context = []
    ) : string
    {
        return $message . ' | ' .  $level . ' | ' . $date;
    }
}