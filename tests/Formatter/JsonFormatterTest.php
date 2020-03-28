<?php

namespace flotzilla\Logger\Test\Formatter;

use flotzilla\Logger\Formatter\JsonFormatter;
use flotzilla\Logger\LogLevel\LogLevel;
use PHPUnit\Framework\TestCase;

class JsonFormatterTest extends TestCase
{
    /**
     * @var JsonFormatter
     */
    protected $formatter;

    protected function setUp()
    {
        $this->formatter = new JsonFormatter();
    }

    /**
     * @skipTest
     */
    public function testFormat()
    {
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );

        $record = [
            'message' => 'Test message',
            'level' => 'info',
            'date' => date('Y-m-d H:i:s')
        ];

        $record2 = [
            'message' => 'Test message 2',
            'level' => 'info',
            'date' => date('Y-m-d H:i:s'),
            'context' => [
                'some additional data' => 123,
                'some additional data2' => 321,
            ]
        ];

        $formattedRecord = $this->formatter->format('Test message', LogLevel::INFO, date('Y-m-d H:i:s'));
        $formattedRecord .= $this->formatter->format('Test message 2', LogLevel::INFO, date('Y-m-d H:i:s'), [
                'some additional data' => 123,
                'some additional data2' => 321,
            ]
        );

        $expected = json_encode($record) . PHP_EOL
            .  json_encode($record2) . PHP_EOL;

        $this->assertEquals($expected, $formattedRecord);
    }
}
