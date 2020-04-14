<?php

declare(strict_types=1);

namespace flotzilla\Logger\Handler;

use flotzilla\Logger\Formatter\FormatterInterface;
use flotzilla\Logger\LogLevel\LogLevel;

class ConsoleHandler implements HandlerInterface
{
    /** @var FormatterInterface */
    protected $formatter;

    /**
     * ConsoleHandler constructor.
     * @param FormatterInterface $formatter
     */
    public function __construct(FormatterInterface $formatter)
    {
        $this->formatter = $formatter;
    }

    /**
     * @inheritDoc
     */
    public function handle(
        string $message = '',
        array $context = [],
        string $level = LogLevel::DEBUG,
        string $date = ''
    ): bool
    {
        $formattedMessage = $this->formatter->format($message, $context, $level, $date);

        echo $formattedMessage;

        return true;
    }
}
