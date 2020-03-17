<?php

declare(strict_types=1);

namespace flotzilla\Logger;

use DateTimeZone;
use Exception;
use flotzilla\Logger\Channel\ChannelInterface;
use flotzilla\Logger\Exception\InvalidLogLevelException;
use flotzilla\Logger\LogLevel\LogLevel;
use Psr\Log\LoggerInterface;
use Psr\Log\LoggerTrait;

class Logger implements LoggerInterface
{
    use LoggerTrait;

    /**
     * @var ChannelInterface[]
     */
    private $channels;

    /** @var DateTimeZone $timeZone */
    private $timeZone;

    /**
     * Logger constructor.
     * @param ChannelInterface[] $channels
     * @param DateTimeZone|null $tz
     */
    public function __construct(array $channels = [], DateTimeZone $tz = null)
    {
        $this->channels = $channels;
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

        // TODO check if string or object have toString method

        // TODO date formatter
        // TODO source
        $record['level'] = $level;
        $record['date'] = date('Y.j.m-h:i:s');
        $record['source'] = 'test source'; //$_SERVER['REMOTE_ADDR']
        $record['message'] = $message;
        $record['context'] = $context;

        foreach ($this->channels as $channel) {
            $channel->handle($record);
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
}
