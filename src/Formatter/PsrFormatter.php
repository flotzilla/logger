<?php

declare(strict_types=1);

namespace flotzilla\Logger\Formatter;

use flotzilla\Logger\LogLevel\LogLevel;

class PsrFormatter implements FormatterInterface
{
    /**
     * @inheritDoc
     */
    public function format(array $record)
    {
        $replace = [];
        $message = $record['message'];

        foreach ($record['context'] as $contextK => $contextV) {
            if (!is_array($contextV) && (!is_object($contextV) || method_exists($contextV, '__toString'))) {
                $replace['{' . $contextK . '}'] = $contextV;
            }
        }

        if ($replace) {
            $message = strtr($record['message'], $replace);
        }

        return $message . PHP_EOL;
    }
}