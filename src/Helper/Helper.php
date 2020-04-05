<?php

declare(strict_types=1);

namespace flotzilla\Logger\Helper;

class Helper
{
    public static function isTimeFormatValid(string $format): bool
    {
        return \DateTime::createFromFormat($format , date($format)) != false;
    }
}
