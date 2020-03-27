<?php

declare(strict_types=1);

namespace flotzilla\Logger\Channel;

use flotzilla\Logger\Handler\HandlerInterface;
use flotzilla\Logger\LogLevel\LogLevel;

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
     * @param string $message
     * @param string $level
     * @param string $date
     * @param array $context
     */
    public function handle(
        string $message = '',
        string $level = LogLevel::DEBUG,
        string $date = '',
        array $context = []
    );
}
