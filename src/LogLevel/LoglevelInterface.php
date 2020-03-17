<?php

declare(strict_types=1);

namespace flotzilla\Logger\LogLevel;

use flotzilla\Logger\Exception\InvalidLogLevelException;

interface LoglevelInterface
{
    /**
     * Should throw exception in attempt to set invalid log level
     *
     * @param string $level
     * @return mixed
     *
     * @throws InvalidLogLevelException
     */
    public function setMaxLogLevel(string $level);

    /**
     * @return string
     */
    public function getMaxLogLevel(): string;

    /**
     * @param string $level
     * @return mixed
     *
     * @throws InvalidLogLevelException
     */
    public function setMinLogLevel(string $level): void;

    /**
     * @return string
     */
    public function getMinLogLevel(): string ;
}
