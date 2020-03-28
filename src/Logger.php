<?php

declare(strict_types=1);

namespace flotzilla\Logger;

use DateTimeZone;
use Exception;
use flotzilla\Logger\Channel\ChannelInterface;
use flotzilla\Logger\Exception\InvalidConfigurationException;
use flotzilla\Logger\Exception\InvalidLogLevelException;
use flotzilla\Logger\LogLevel\LogLevel;
use Psr\Log\LoggerInterface;
use Psr\Log\LoggerTrait;

class Logger implements LoggerInterface
{
    use LoggerTrait;

    /** @var ChannelInterface[] */
    protected $channels;

    /** @var string $dateTimeFormat */
    protected $dateTimeFormat;

    /** @var DateTimeZone $timeZone */
    protected $timeZone;

    /**
     * Logger constructor.
     * @param ChannelInterface[] $channels
     * @param string $dateTimeFormat that can be passed to date() or constant from DateTimeInterface
     * @param DateTimeZone|null $tz
     * @throws InvalidConfigurationException
     */
    public function __construct(array $channels = [], string $dateTimeFormat = 'Y.j.m-h:i:s.u', DateTimeZone $tz = null)
    {
        if (!date($dateTimeFormat)){
            throw new InvalidConfigurationException("Invalid date time format");
        }

        $this->channels = $channels;
        $this->dateTimeFormat = $dateTimeFormat;
        $this->timeZone = $tz ?: new DateTimeZone(date_default_timezone_get());
    }

    /**
     * Logs with an arbitrary level.
     *
     * @param mixed $level
     * @param string $message
     * @param array $context
     *
     * @return void
     *
     * @throws InvalidLogLevelException
     * @throws Exception
     */
    public function log($level, $message, array $context = [])
    {
        if (!in_array(strtolower($level), LogLevel::LOG_LEVELS)) {
            throw new InvalidLogLevelException("{$level} Log level is not exists");
        }

        $date = new \DateTimeImmutable('now', $this->timeZone);

        foreach ($this->channels as $channel) {
            $channel->handle($message, $level, $date->format($this->dateTimeFormat), $context);
        }
    }

    /**
     * @return ChannelInterface[]
     */
    public function getChannels(): array
    {
        return $this->channels;
    }

    /**
     * @param ChannelInterface[] $channels
     */
    public function setChannels(array $channels): void
    {
        $this->channels = $channels;
    }

    /**
     * @return string
     */
    public function getDateTimeFormat(): string
    {
        return $this->dateTimeFormat;
    }

    /**
     * @return DateTimeZone
     */
    public function getTimeZone(): DateTimeZone
    {
        return $this->timeZone;
    }
}
