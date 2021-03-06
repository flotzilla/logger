<?php

declare(strict_types=1);

namespace flotzilla\Logger\Formatter;

use flotzilla\Logger\Helper\Helper;
use flotzilla\Logger\LogLevel\LogLevel;

class SimpleLineFormatter implements FormatterInterface
{
    /** @var string $dataSeparatorStart */
    protected $dataSeparatorStart;

    /** @var string $dataSeparatorEnd */
    protected $dataSeparatorEnd;

    /** @var string $contextSeparatorStart */
    protected $contextSeparatorStart;

    /** @var string */
    protected $contextSeparatorEnd;

    /**
     * SimpleFormatter constructor.
     * @param string $dataSeparatorStart
     * @param string $dataSeparatorEnd
     * @param string $contextSeparatorStart
     * @param string $contextSeparatorEnd
     */
    public function __construct(
        string $dataSeparatorStart = '[',
        string $dataSeparatorEnd = ']',
        string $contextSeparatorStart = '{',
        string $contextSeparatorEnd = '}'
    )
    {
        $this->dataSeparatorStart = $dataSeparatorStart;
        $this->dataSeparatorEnd = $dataSeparatorEnd;
        $this->contextSeparatorStart = $contextSeparatorStart;
        $this->contextSeparatorEnd = $contextSeparatorEnd;
    }

    /**
     * @inheritDoc
     */
    public function format(
        string $message = '',
        array $context = [],
        string $level = LogLevel::DEBUG,
        string $date = ''
    ): string
    {
        $parsedContext = $context ? $this->parseContext($context) : '';

        return $this->dataSeparatorStart . $date . $this->dataSeparatorEnd
            . $this->dataSeparatorStart . strtoupper($level) . $this->dataSeparatorEnd
            . $this->dataSeparatorStart . $message . $this->dataSeparatorEnd
            . ($parsedContext ? $this->dataSeparatorStart . $parsedContext . $this->dataSeparatorEnd : '');
    }

    /**
     * @param array $context
     * @return string
     */
    private function parseContext(array $context): string
    {
        $parsedContext = '';

        $count = count($context);
        if ($count == 0) {
            return $parsedContext;
        }

        // check if context array have associative keys
        if (array_keys($context) !== range(0, $count - 1)) {
            foreach ($context as $elementK => $elementV) {
                if (Helper::checkIsLineParsable($elementV)) {
                    $parsedContext .= $this->contextSeparatorStart . $elementK . '=' . $elementV . $this->contextSeparatorEnd;
                }
            }
        } else {
            foreach ($context as $contextEl) {
                if (Helper::checkIsLineParsable($contextEl)) {
                    $parsedContext .= $this->contextSeparatorStart . $contextEl . $this->contextSeparatorEnd;
                }
            }
        }

        return $parsedContext;
    }
}
