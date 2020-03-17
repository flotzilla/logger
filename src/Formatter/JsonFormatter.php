<?php

declare(strict_types=1);

namespace flotzilla\Logger\Formatter;

class JsonFormatter implements FormatterInterface
{

    public function format(array $record)
    {
        // TODO implement more sophisticated approach
        return json_encode($record) . PHP_EOL;
    }
}
