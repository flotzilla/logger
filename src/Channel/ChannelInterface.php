<?php

declare(strict_types=1);

namespace flotzilla\Logger\Channel;

use flotzilla\Logger\Exception\InvalidConfigurationException;
use flotzilla\Logger\Handler\HandlerInterface;
use flotzilla\Logger\LogLevel\LogLevel;

interface ChannelInterface
{
    /**
     * @param HandlerInterface[] $handlers
     * @throws InvalidConfigurationException
     */
    public function setHandlers(array $handlers): void;

    /**
     * @return HandlerInterface[]
     */
    public function getHandlers(): array;

    /**
     * @param HandlerInterface $handler
     * @return mixed
     */
    public function addHandler(HandlerInterface $handler);

    /**
     * @return string
     */
    public function getChannelName(): string;

    /**
     * Push log message to all subscribed handlers. Return boolean true if there is no errors,
     * otherwise return array with errors.
     * Return true in case if channel was disabled or channel log level is not appropriate
     * (loglevel did not pass min/max checks)
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

    /**
     * Is current channel enabled
     * @return bool
     */
    public function isEnabled(): bool;

    /**
     * Set state of the channel - disable for logs write or enable them
     * @param bool $enabled
     */
    public function setEnabled(bool $enabled): void;
}
