<?php

namespace flotzilla\Logger\Test\Formatter;

use flotzilla\Logger\Formatter\JsonFormatter;
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

    public function testFormat()
    {
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

        $formattedRecord = $this->formatter->format($record);
        $formattedRecord .= $this->formatter->format($record2);

        $expected = json_encode($record) . PHP_EOL
            .  json_encode($record2) . PHP_EOL;

        $this->assertEquals($expected, $formattedRecord);
    }
}
