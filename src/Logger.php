<?php

declare(strict_types=1);

namespace flotzilla\Logger;

use DateTimeImmutable;
use DateTimeZone;
use Exception;
use flotzilla\Logger\Channel\ChannelInterface;
use flotzilla\Logger\Exception\InvalidConfigurationException;
use flotzilla\Logger\Exception\InvalidLogLevelException;
use flotzilla\Logger\Exception\LoggerErrorStackException;
use flotzilla\Logger\Helper\Helper;
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
    public function __construct(
        array $channels = [],
        string $dateTimeFormat = 'Y.j.m-h:i:s.u',
        DateTimeZone $tz = null
    )
    {
        if (!Helper::isTimeFormatValid($dateTimeFormat)) {
            throw new InvalidConfigurationException('Invalid date time format');
        }

        $this->channels = $channels;
        $this->dateTimeFormat = $dateTimeFormat;
        $this->timeZone = $tz ?: new DateTimeZone(date_default_timezone_get());
    }

    /**
     * Logs with an arbitrary level.
     *
     * @param mixed $level LogLevel formats
     * @param string $message message to log
     * @param array $context additional data
     *
     * @return void
     *
     * Throw errors if no any error handler was provided.
     * @throws InvalidLogLevelException in case of wrong level format
     * @throws Exception cause of DateTimeImmutable creation
     * @throws LoggerErrorStackException that can contain multiple exceptions from multiple sources e.g. exceptions
     * from handlers and formatters
     * @see LogLevel for correct $level log formats
     *
     */
    public function log($level, $message, array $context = [])
    {
        if (!in_array(strtolower($level), LogLevel::LOG_LEVELS)) {
            throw new InvalidLogLevelException("{$level} Log level is not exist");
        }

        $date = new DateTimeImmutable('now', $this->timeZone);

        $loggerErrors = new LoggerErrorStackException();
        foreach ($this->channels as $channel) {
            $response = $channel->handle($message, $context, $level, $date->format($this->dateTimeFormat));

            if (is_array($response)) {
                $loggerErrors->mergeErrors($response);
            }
        }

        if (count($loggerErrors) > 0){
            throw $loggerErrors;
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

