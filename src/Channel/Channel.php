<?php

declare(strict_types=1);

namespace flotzilla\Logger\Channel;

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
     * @param array $handlers
     * @param string|null $maxLogLevel
     * @param string|null $minLogLevel
     *
     * @throws InvalidLogLevelException
     */
    public function __construct(
        string $channelName,
        array $handlers = [],
        string $maxLogLevel = null,
        string $minLogLevel = null
    )
    {
        $this->channelName = $channelName;
        $this->handlers = $handlers;

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
     * @inheritDoc
     */
    public function handle(array $record)
    {
        if (!$this->enabled) {
            return;
        }

        if (!$this->maxLogLevelCheck($record['level'], $this->maxLogLevel)
            || !$this->minLogLevelCheck($record['level'], $this->minLogLevel)) {
            return;
        }

        foreach ($this->handlers as $handler) {
            $handler->handle($record);
        }
    }

    /**
     * @inheritDoc
     */
    public function setHandlers(array $handlers): void
    {
        $this->handlers = $handlers;
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
    public function addHandler(HandlerInterface $handler, string $handlerName = null)
    {
        $handlerName
            ? $this->handlers[$handlerName] = $handler
            : $this->handlers[] = $handler;
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
    public function setMaxLogLevel(string $level)
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
