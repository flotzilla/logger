<?php

declare(strict_types=1);

namespace flotzilla\Logger\Channel;

use flotzilla\Logger\LogLevel\LogLevel;
use flotzilla\Logger\LogLevel\LoglevelInterface;

class NullChannel implements ChannelInterface, LoglevelInterface
{
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

    /**
     * @inheritDoc
     */
    public function isEnabled(): bool
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    public function setEnabled(bool $enabled): void
    {
    }

    /**
     * @inheritDoc
     */
    public function getMaxLogLevel(): string
    {
        return LogLevel::DEBUG;
    }

    /**
     * @inheritDoc
     */
    public function setMaxLogLevel(string $level): void
    {
    }

    /**
     * @inheritDoc
     */
    public function setMinLogLevel(string $level): void
    {
    }

    /**
     * @inheritDoc
     */
    public function getMinLogLevel(): string
    {
        return LogLevel::EMERGENCY;
    }
}
