<?php

declare(strict_types=1);

namespace flotzilla\Logger\Formatter;

interface FormatterInterface
{
    public function format(array $record);
}
