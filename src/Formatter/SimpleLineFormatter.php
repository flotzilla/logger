<?php

declare(strict_types=1);

namespace flotzilla\Logger\Formatter;

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

    public function format(array $record)
    {
        $parsedContext = isset($record['context']) ? $this->parseContext($record['context']) : null;

        return $this->dataSeparatorStart . $record['date'] . $this->dataSeparatorEnd
        . $this->dataSeparatorStart . strtoupper($record['level']) . $this->dataSeparatorEnd
        . $this->dataSeparatorStart . $record['source'] . $this->dataSeparatorEnd
        . $this->dataSeparatorStart . $record['message'] . $this->dataSeparatorEnd
        . ($parsedContext ? $this->dataSeparatorStart . $parsedContext . $this->dataSeparatorEnd : '') . PHP_EOL;
    }

    /**
     * @param array|string $context
     * @return string
     */
    private function parseContext($context)
    {
        $parsedContext = '';

        if (is_array($context)) {

            $count = count($context);
            if ($count == 0) {
                return $parsedContext;
            }

            // check if context array have associative keys
            if (array_keys($context) !== range(0, $count - 1)) {
                foreach ($context as $elementK => $elementV) {
                    $parsedContext .= $this->dataSeparatorStart . $elementK . '=' . $elementV . $this->dataSeparatorEnd;
                }
            } else {
                foreach ($context as $contextEl) {
                    $parsedContext .= $this->dataSeparatorStart . $contextEl . $this->dataSeparatorEnd;
                }
            }
        } else if (is_string($context)) {

            if ($context) {
                $parsedContext = $this->dataSeparatorStart . $context . $this->dataSeparatorEnd;
            }
        } else {
            // TODO handle
        }

        return $parsedContext;
    }
}
