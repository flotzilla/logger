<?php

declare(strict_types=1);

namespace flotzilla\Logger\Helper;

class Helper
{
    /**
     * Check if string can be parsed as php date format
     * @see https://www.php.net/manual/en/function.date.php
     * @param string $format
     * @return bool
     */
    public static function isTimeFormatValid(string $format): bool
    {
        return \DateTime::createFromFormat($format, date($format)) != false;
    }

    /**
     * Check if element can be converted to string
     * @param mixed $lineElement
     * @return bool
     */
    public static function checkIsLineParsable($lineElement): bool
    {
        return !is_array($lineElement) && !is_resource($lineElement)
            && (!is_object($lineElement) || method_exists($lineElement, '__toString'));
    }
}
