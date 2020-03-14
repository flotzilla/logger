<?php

declare(strict_types=1);

namespace flotzilla\Logger;

use Exception;
use flotzilla\Logger\Handler\HandlerInterface;
use Psr\Log\InvalidArgumentException;
use Psr\Log\LoggerInterface;
use Psr\Log\LoggerTrait;

class Logger implements LoggerInterface
{
    use LoggerTrait;

    public const DI = 'LoggerDI';

    /**
     * @var string
     */
    private $channelName;
    /**
     * @var array
     */
    private $handlers;

    /**
     * Logger constructor.
     * @param string $channelName
     * @param HandlerInterface[] $handlers
     */
    public function __construct(string $channelName, array $handlers = [])
    {
        $this->channelName = $channelName;
        $this->handlers = $handlers;
    }

    /**
     * Logs with an arbitrary level.
     *
     * @param mixed $level
     * @param string $message
     * @param array $context
     *
     * @return void
     *
     * @throws InvalidArgumentException
     * @throws Exception
     */
    public function log($level, $message, array $context = [])
    {
        if (!in_array($level, LogLevel::LOG_LEVELS)){
            throw new InvalidArgumentException("{$level} Log level is not exists");
        }

        // TODO check if string or object have toString method

        // TODO date formatter
        // TODO source
        $record['level'] = $level;
        $record['date'] = date('Y.j.m-h:i:s');
        $record['source'] = 'test source'; //$_SERVER['REMOTE_ADDR']
        $record['context'] = $context;
        $record['message'] = $message;

        foreach ($this->handlers as $handler) {
            $handler->handle($record);
        }
    }

    /**
     * @return string
     */
    public function getChannelName(): string
    {
        return $this->channelName;
    }

    /**
     * @return HandlerInterface[]
     */
    public function getHandlers(): array
    {
        return $this->handlers;
    }
}
