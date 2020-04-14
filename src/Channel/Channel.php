<?php

declare(strict_types=1);

namespace flotzilla\Logger\Channel;

use flotzilla\Logger\Exception\FormatterException;
use flotzilla\Logger\Exception\HandlerException;
use flotzilla\Logger\Exception\InvalidChannelNameException;
use flotzilla\Logger\Exception\InvalidConfigurationException;
use flotzilla\Logger\Exception\InvalidLogLevelException;
use flotzilla\Logger\LogLevel\LogLevel;
use flotzilla\Logger\LogLevel\LoglevelInterface;
use flotzilla\Logger\LogLevel\LogLevelTrait;
use flotzilla\Logger\Handler\HandlerInterface;

class Channel implements ChannelInterface, LoglevelInterface
{
    use LogLevelTrait;

    /** @var string $channelName */
    private $channelName;

    /** @var HandlerInterface[] $handlers */
    private $handlers;

    /** @var bool $enabled */
    private $enabled = true;

    /** @var string $maxLogLevel */
    private $maxLogLevel;

    /** @var string $minLogLevel */
    private $minLogLevel;

    /**
     * Channel constructor.
     * @param string $channelName
     * @param HandlerInterface[] $handlers
     * @param string|null $maxLogLevel
     * @param string|null $minLogLevel
     *
     * @throws InvalidLogLevelException
     * @throws InvalidChannelNameException
     * @throws InvalidConfigurationException
     */
    public function __construct(
        string $channelName,
        array $handlers = [],
        string $maxLogLevel = null,
        string $minLogLevel = null
    )
    {
        if (!$channelName){
            throw new InvalidChannelNameException();
        }

        $this->setHandlers($handlers);

        $this->channelName = $channelName;

        if ($maxLogLevel === null) {
            $maxLogLevel = LogLevel::DEBUG;
        } else if (!$this->isLogLevelValid($maxLogLevel)) {
            throw new InvalidLogLevelException("Invalid {$maxLogLevel} max level parameter");
        }

        if ($minLogLevel === null) {
            $minLogLevel = LogLevel::EMERGENCY;
        } else if (!$this->isLogLevelValid($minLogLevel)) {
            throw new InvalidLogLevelException("Invalid {$minLogLevel} max level parameter");
        }

        $this->maxLogLevel = strtolower($maxLogLevel);
        $this->minLogLevel = strtolower($minLogLevel);
    }

    /**
     * @param array $handlers
     * @throws InvalidConfigurationException
     */
    private function setHandlers(array $handlers): void
    {
        foreach ($handlers as $handler) {
            if (!$handler instanceof HandlerInterface){
                throw new InvalidConfigurationException('Array arguments should be instance of HandlerInterface');
            }

            $this->handlers[] = $handler;
        }
    }

    /**
     * @inheritDoc
     */
    public function handle(
        string $message = '',
        array $context = [],
        string $level = LogLevel::DEBUG,
        string $date = ''
    )
    {
        $errors = [];
        $isSuccess = true;

        if (!$this->enabled) {
            return $isSuccess;
        }

        if (!$this->maxLogLevelCheck($level, $this->maxLogLevel)
            || !$this->minLogLevelCheck($level, $this->minLogLevel)) {
            return $isSuccess;
        }

        foreach ($this->handlers as $handler) {
            try {
                $isSuccess &= $handler->handle($message, $context, $level, $date);
            } catch (HandlerException | FormatterException $e) {
                $errors[] = $e;
                continue;
            }
        }

        return $errors ?: $isSuccess;
    }

    /**
     * @inheritDoc
     */
    public function getHandlers(): array
    {
        return $this->handlers;
    }

    /**
     * @inheritDoc
     */
    public function getChannelName(): string
    {
        return $this->channelName;
    }

    /**
     * @return bool
     */
    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    /**
     * @param bool $enabled
     */
    public function setEnabled(bool $enabled): void
    {
        $this->enabled = $enabled;
    }

    /**
     * @inheritDoc
     */
    public function getMaxLogLevel(): string
    {
        return $this->maxLogLevel;
    }

    /**
     * @inheritDoc
     */
    public function setMaxLogLevel(string $level): void
    {
        if (!$this->isLogLevelValid($level)) {
            throw new InvalidLogLevelException("Invalid {$level} max level parameter");
        }

        $this->maxLogLevel = strtolower($level);
    }

    /**
     * @inheritDoc
     */
    public function setMinLogLevel(string $level): void
    {
        if (!$this->isLogLevelValid($level)) {
            throw new InvalidLogLevelException("Invalid {$level} max level parameter");
        }

        $this->minLogLevel = strtolower($level);
    }

    /**
     * @inheritDoc
     */
    public function getMinLogLevel(): string
    {
        return $this->minLogLevel;
    }
}
