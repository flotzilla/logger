<?php

declare(strict_types=1);

namespace flotzilla\Logger\Channel;

use flotzilla\Logger\Handler\HandlerInterface;
use flotzilla\Logger\LogLevel\LogLevel;

class NullChannel implements ChannelInterface
{
    /**
     * @inheritDoc
     */
    public function setHandlers(array $handlers): void
    {

    }

    /**
     * @inheritDoc
     */
    public function getHandlers(): array
    {
        return [];
    }

    /**
     * @inheritDoc
     */
    public function addHandler(HandlerInterface $handler, string $handlerName = null)
    {
    }

    /**
     * @inheritDoc
     */
    public function getChannelName(): string
    {
        return 'NullChannel';
    }

    /**
     * @inheritDoc
     */
    public function handle(string $message = '', array $context = [], string $level = LogLevel::DEBUG, string $date = '')
    {
        return true;
    }
}
