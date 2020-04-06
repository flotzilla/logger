<?php

namespace flotzilla\Logger\Test\Formatter;

use flotzilla\Logger\Formatter\PsrFormatter;
use flotzilla\Logger\LogLevel\LogLevel;
use PHPUnit\Framework\TestCase;

class PsrFormatterTest extends TestCase
{
    /**
     * @var PsrFormatter
     */
    protected $formatter;

    protected function setUp()
    {
        $this->formatter = new PsrFormatter();
    }

    public function testFormatEmpty()
    {
        $response = $this->formatter->format();
        $this->assertTrue(true);
        $this->assertEquals('', $response);
    }

    public function testFormatSimpleLine()
    {
        $response = $this->formatter->format('str');
        $this->assertTrue(true);
        $this->assertEquals('str', $response);
    }

    public function testFormatSimpleLineWithNonEmptyParams()
    {
        $response = $this->formatter->format('str', ['str' => 123]);
        $this->assertTrue(true);
        $this->assertEquals('str', $response);
    }

    public function testFormatReplace()
    {
        $response = $this->formatter->format('{str}', ['str' => 123]);
        $this->assertTrue(true);
        $this->assertEquals('123', $response);
    }

    public function testFormatReplaceMultiple()
    {
        $response = $this->formatter->format('{str} {sss}', ['str' => 123, 'sss' => 'www']);
        $this->assertTrue(true);
        $this->assertEquals('123 www', $response);
    }

    public function testFormatReplaceMultipleWithFullParams()
    {
        $response = $this->formatter->format(
            '{str} {sss}',
            ['str' => 123, 'sss' => 'www'],
            LogLevel::CRITICAL,
            'some date');
        $this->assertTrue(true);
        $this->assertEquals('123 www', $response);
    }

    public function testFormatReplaceWithNonExistedKeyObjects()
    {
        $response = $this->formatter->format('{str}', [new \stdClass()]);
        $this->assertTrue(true);
        $this->assertEquals('{str}', $response);
    }

    public function testFormatReplaceWithExistedKeyObjects()
    {
        $response = $this->formatter->format('{str}', [ 'str' => new \stdClass()]);
        $this->assertTrue(true);
        $this->assertEquals('{str}', $response);
    }
}
