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

    public function testFormat()
    {
        $date = date('Y-m-d H:i:s');

        $formattedRecord = $this->formatter->format('Test message', [], LogLevel::INFO, date('Y-m-d H:i:s'));
        $formattedRecord .= $this->formatter->format(
            'Test message 2',
            [
                'some additional data' => 123,
                'some additional data2' => 321,
            ],
            LogLevel::INFO,
            $date
        );

$expected = '{
    "date": "' . $date. '",
    "message": "Test message",
    "level": "INFO",
    "data": "{}"
}{
    "date": "' . $date. '",
    "message": "Test message 2",
    "level": "INFO",
    "data": "{
    "some additional data": 123,
    "some additional data2": 321
}"
}';

        $this->assertEquals($expected, $formattedRecord);
    }
}
