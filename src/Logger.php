<?php

declare(strict_types=1);

namespace flotzilla\Logger;

use DateTimeImmutable;
use DateTimeZone;
use Exception;
use flotzilla\Logger\Channel\ChannelInterface;
use flotzilla\Logger\Exception\FormatterException;
use flotzilla\Logger\Exception\HandlerException;
use flotzilla\Logger\Exception\InvalidConfigurationException;
use flotzilla\Logger\Exception\InvalidLogLevelException;
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

    // TODO fix this
    protected $errorHandler;

    /**
     * Logger constructor.
     * @param ChannelInterface[] $channels
     * @param string $dateTimeFormat that can be passed to date() or constant from DateTimeInterface
     * @param DateTimeZone|null $tz
     * @throws InvalidConfigurationException
     */
    public function __construct(array $channels = [], string $dateTimeFormat = 'Y.j.m-h:i:s.u', DateTimeZone $tz = null)
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
     * @throws FormatterException in case of formatting issues, e.g. parsing invalid json
     * @throws HandlerException in case of handler error, e.g file write exception
     * @throws InvalidLogLevelException in case of wrong level format
     * @throws Exception cause of DateTimeImmutable creation
     * @see LogLevel for correct log formats
     *
     */
    public function log($level, $message, array $context = [])
    {
        try {
            if (!in_array(strtolower($level), LogLevel::LOG_LEVELS)) {
                throw new InvalidLogLevelException("{$level} Log level is not exist");
            }

            $date = new DateTimeImmutable('now', $this->timeZone);
        } catch (InvalidLogLevelException | Exception $e) {
            $this->handleErrors([$e]);
        }

        $errors = [];
        //TODO date check
        foreach ($this->channels as $channel) {
            $response = $channel->handle($message, $context, $level, $date->format($this->dateTimeFormat));

            // TODO handle errors from each channel
            if (!$response && is_array($response)) {
                array_merge($errors, $response);
            }
        }

        if ($errors){
            $this->handleErrors($errors);
        }

    }

    /**
     * @param array $errors
     * @throws Exception
     */
    public function handleErrors(array $errors)
    {
        foreach ($errors as $error) {
            if ($this->errorHandler) {
                $this->errorHandler->handle($error);
            } else {
                // TODO whoa! what we got here?
                throw $error;
            }
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

    /**
     * @return mixed
     */
    public function getErrorHandler()
    {
        return $this->errorHandler;
    }

    /**
     * @param mixed $errorHandler
     */
    public function setErrorHandler($errorHandler): void
    {
        $this->errorHandler = $errorHandler;
    }
}
