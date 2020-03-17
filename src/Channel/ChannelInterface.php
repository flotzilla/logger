<?php

declare(strict_types=1);

namespace flotzilla\Logger\Channel;

use flotzilla\Logger\Handler\HandlerInterface;

interface ChannelInterface
{
    /**
     * @param HandlerInterface[] $handlers
     */
    public function setHandlers(array $handlers): void;

    /**
     * @return HandlerInterface[]
     */
    public function getHandlers(): array;

    /**
     * @param HandlerInterface $handler
     * @param string|null $handlerName
     * @return mixed
     */
    public function addHandler(HandlerInterface $handler, string $handlerName = null);

    /**
     * @return string
     */
    public function getChannelName(): string;

    /**
     * @param array $record
     */
    public function handle(array $record);
}
