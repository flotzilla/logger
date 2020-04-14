<?php

namespace flotzilla\Logger\Test\Handler;

use flotzilla\Logger\Exception\FormatterException;
use flotzilla\Logger\Formatter\SimpleLineFormatter;
use flotzilla\Logger\Handler\ConsoleHandler;
use flotzilla\Logger\LogLevel\LogLevel;
use PHPUnit\Framework\TestCase;

class ConsoleHandlerTest extends TestCase
{
    public function testHandle()
    {
        $f = new SimpleLineFormatter();
        $ch = new ConsoleHandler($f);

        $resp = $ch->handle('test message', [], LogLevel::DEBUG, date('Y.j.m-h:i:s.u'));

        $this->assertTrue($resp);
    }

    public function testFormatterException()
    {
        $this->expectException(FormatterException::class);
        $formatterMock = $this->createMock(SimpleLineFormatter::class);
        $formatterMock->method('format')
            ->willThrowException(new FormatterException);

        $ch = new ConsoleHandler($formatterMock);
        $resp = $ch->handle('test message', [], LogLevel::DEBUG, date('Y.j.m-h:i:s.u'));
    }
}
