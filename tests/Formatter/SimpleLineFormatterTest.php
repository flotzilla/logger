<?php

namespace flotzilla\Logger\Test\Formatter;

use flotzilla\Logger\Formatter\SimpleLineFormatter;
use PHPUnit\Framework\TestCase;
use Psr\Log\LogLevel;

class SimpleLineFormatterTest extends TestCase
{
    /** @var SimpleLineFormatter */
    protected $formatter;

    protected function setUp()
    {
        $this->formatter = new SimpleLineFormatter;
    }

    public function testFormatEmpty()
    {
        $response = $this->formatter->format();
        $this->assertEquals('[][DEBUG][]', $response);
    }

    public function testSimpleMessage()
    {
        $response = $this->formatter->format('some mess');
        $this->assertEquals('[][DEBUG][some mess]', $response);
    }

    public function testSimpleMessageWithDate()
    {
        $response = $this->formatter->format('some mess', [], LogLevel::DEBUG, '12-02-2002');
        $this->assertEquals('[12-02-2002][DEBUG][some mess]', $response);
    }

    public function testSimpleMessageWithDateAndParams()
    {
        $response = $this->formatter->format('some mess', [123], LogLevel::DEBUG, '12-02-2002');
        $this->assertEquals('[12-02-2002][DEBUG][some mess][{123}]', $response);
    }

    public function testSimpleMessageWithDateAndAnotherParams()
    {
        $response = $this->formatter->format('some mess', ['www' => 123], LogLevel::DEBUG, '12-02-2002');
        $this->assertEquals('[12-02-2002][DEBUG][some mess][{www=123}]', $response);
    }

    public function testSimpleMessageWithDateAndAnotherCoupleOfParams()
    {
        $response = $this->formatter->format('some mess', ['www' => 123, 'ddd' => 321], LogLevel::DEBUG, '12-02-2002');
        $this->assertEquals('[12-02-2002][DEBUG][some mess][{www=123}{ddd=321}]', $response);
    }

    public function testSimpleMessageWithDateAndAnotherCoupleOfParamsWithDifferenetSeparators()
    {
        $this->formatter = new SimpleLineFormatter('(', ')', '[', ']');
        $response = $this->formatter->format('some mess', ['www' => 123, 'ddd' => 321], LogLevel::DEBUG, '12-02-2002');
        $this->assertEquals('(12-02-2002)(DEBUG)(some mess)([www=123][ddd=321])', $response);
    }

    public function testFormatObject()
    {
        $response = $this->formatter->format('some mess', ['www' => new \stdClass(), 'sss'=> 123], LogLevel::DEBUG, '12-02-2002');
        $this->assertEquals('[12-02-2002][DEBUG][some mess][{sss=123}]', $response);
    }
}
