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
     * Push log message to all subscribed handlers. Return boolean true if there is no errors, else array with errors.
     * Return true in case if channel was disabled or channel log level is not appropriate (do not pass min/max loglevel checks)
     *
     * @param string $message
     * @param array $context
     * @param string $level
     * @param string $date
     *
     * @return mixed
     */
    public function handle(
        string $message = '',
        array $context = [],
        string $level = LogLevel::DEBUG,
        string $date = ''
    );
}
