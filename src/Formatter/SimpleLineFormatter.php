<?php

declare(strict_types=1);

namespace flotzilla\Logger\Formatter;

use flotzilla\Logger\LogLevel\LogLevel;

class SimpleLineFormatter implements FormatterInterface
{
    /** @var string $dataSeparatorStart */
    protected $dataSeparatorStart = "[";

    /** @var string $dataSeparatorEnd */
    protected $dataSeparatorEnd = "]";

    /**
     * SimpleFormatter constructor.
     * @param string $dataSeparatorStart
     * @param string $dataSeparatorEnd
     */
    public function __construct(string $dataSeparatorStart = "[", string $dataSeparatorEnd = "]")
    {
        $this->dataSeparatorStart = $dataSeparatorStart;
        $this->dataSeparatorEnd = $dataSeparatorEnd;
    }

    public function format(
        string $message = '',
        string $level = LogLevel::DEBUG,
        string $date = '',
        array $context = []
    ): string
    {
        $parsedContext = $context ? $this->parseContext($context) : '';

        return $this->dataSeparatorStart . $date . $this->dataSeparatorEnd
            . $this->dataSeparatorStart . strtoupper($level) . $this->dataSeparatorEnd
            . $this->dataSeparatorStart . $message . $this->dataSeparatorEnd
            . ($parsedContext ? $this->dataSeparatorStart . $parsedContext . $this->dataSeparatorEnd : '') . PHP_EOL;
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
                if ($this->isContextLineParsable($elementV)) {
                    $parsedContext .= $this->dataSeparatorStart . $elementK . '=' . $elementV . $this->dataSeparatorEnd;
                }
            }
        } else {
            foreach ($context as $contextEl) {
                if ($this->isContextLineParsable($contextEl)) {
                    $parsedContext .= $this->dataSeparatorStart . $contextEl . $this->dataSeparatorEnd;
                }
            }
        }

        return $parsedContext;
    }

    /**
     *
     * Check if element can be converted to string
     *
     * @param mixed $lineElement
     * @return bool
     */
    private function isContextLineParsable($lineElement): bool
    {
        return !is_array($lineElement) && !is_resource($lineElement)
            && (!is_object($lineElement) || method_exists($lineElement, '__toString'));
    }
}
