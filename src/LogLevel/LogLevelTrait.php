<?php

declare(strict_types=1);

namespace flotzilla\Logger\LogLevel;

trait LogLevelTrait
{
    /**
     * @param string $level
     * @return mixed
     */
    public function isLogLevelValid(string $level)
    {
        return in_array(strtolower($level), LogLevel::LOG_LEVELS);
    }

    /**
     * @param string $level
     * @param string $maxLevel
     * @return bool
     */
    public function maxLogLevelCheck(string $level, string $maxLevel): bool
    {
        return $this->isLogLevelValid($level) && $this->isLogLevelValid($maxLevel)
            && LogLevel::LOG_LEVELS_INT[strtolower($level)] <= LogLevel::LOG_LEVELS_INT[$maxLevel];
    }

    /**
     * @param string $level
     * @param string $minLevel
     * @return bool
     */
    public function minLogLevelCheck(string $level, string $minLevel): bool
    {
        return $this->isLogLevelValid($level) && $this->isLogLevelValid($minLevel)
            && LogLevel::LOG_LEVELS_INT[strtolower($level)] >= LogLevel::LOG_LEVELS_INT[$minLevel];
    }

}