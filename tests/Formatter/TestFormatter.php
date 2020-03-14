<?php


namespace flotzilla\Logger\Test\Formatter;


use flotzilla\Logger\Formatter\FormatterInterface;

class TestFormatter implements FormatterInterface
{

    public function format(array $record)
    {
        return $record['message'];
    }
}