<?php

declare(strict_types=1);

namespace flotzilla\Logger\Formatter {
    function json_encode()
    {
        throw new \JsonException();
    }
}

namespace flotzilla\Logger\Test\MockFormatter {

    use flotzilla\Logger\Exception\FormatterException;
    use flotzilla\Logger\Formatter\JsonFormatter;
    use PHPUnit\Framework\TestCase;

    class JsonFormatterErrorTest extends TestCase
    {
        /**
         * @var JsonFormatter
         */
        protected $formatter;

        protected function setUp()
        {
            $this->formatter = new JsonFormatter();
        }

        public function testException()
        {
            $this->expectException(FormatterException::class);

            $date = date('Y-m-d H:i:s');

            $this->formatter->format('some str', ['data' => ['dd' => 123]]);
        }
    }
}
