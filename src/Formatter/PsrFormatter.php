<?php

declare(strict_types=1);

namespace flotzilla\Logger\Formatter;

use flotzilla\Logger\LogLevel\LogLevel;

class PsrFormatter implements FormatterInterface
{
    /**
     * String implementation of PSR logging interface, with skipped values of log level and date
     *
     * @param string $message
     * @param array $context
     * @param string $level skipped parameter
     * @param string $date skipped parameter
     *
     * @return string result string
     */
    public function format(
        string $message = '',
        array $context = [],
        string $level = LogLevel::DEBUG,
        string $date = ''
    ): string
    {
        $replace = [];

        foreach ($context as $contextK => $contextV) {
            if (!is_array($contextV) && (!is_object($contextV) || method_exists($contextV, '__toString'))) {
                $replace['{' . $contextK . '}'] = $contextV;
            }
        }

        if ($replace) {
            $message = strtr($message, $replace);
        }

        return $message;
    }
}
