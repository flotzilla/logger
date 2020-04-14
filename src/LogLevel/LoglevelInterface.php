<?php

declare(strict_types=1);

namespace flotzilla\Logger\LogLevel;

use flotzilla\Logger\Exception\InvalidLogLevelException;

interface LoglevelInterface
{
    /**
     * @return string
     */
    public function getMaxLogLevel(): string;

    /**
     * Set maximum allowed level for channel, levels higher that $level will be ignored
     * @param string $level
     * @return void
     *
     * @throws InvalidLogLevelException
     *
     * @see LogLevel for setting correct log level
     */
    public function setMaxLogLevel(string $level): void;

    /**
     * Set minimal allowed level for channel, levels lover that $level will be ignored
     * @param string $level
     * @return void
     *
     * @throws InvalidLogLevelException
     *
     * @see LogLevel for setting correct log level
     */
    public function setMinLogLevel(string $level): void;

    /**
     * @return string
     */
    public function getMinLogLevel(): string;
}
