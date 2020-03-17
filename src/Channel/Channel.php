<?php

declare(strict_types=1);

namespace flotzilla\Logger\Channel;

use Generator;
use flotzilla\Logger\Handler\HandlerInterface;

class Channel implements ChannelInterface
{
    /** @var string $channelName */
    private $channelName;

    /** @var HandlerInterface[] $handlers */
    private $handlers;

    /** @var bool $enabled */
    private $enabled = true;

    private $maxLogLevel;

    /**
     * Channel constructor.
     * @param string $channelName
     * @param HandlerInterface[] $handlers
     */
    public function __construct(string $channelName, array $handlers = [])
    {
        $this->channelName = $channelName;
        $this->handlers = $handlers;
    }

    /**
     * @inheritDoc
     */
    public function handle(array $record)
    {
        if (!$this->enabled) {
            return;
        }

        // TODO check for max log level

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
}
