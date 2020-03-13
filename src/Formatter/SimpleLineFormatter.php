<?php

declare(strict_types=1);

namespace flotzilla\Logger\Formatter;

class SimpleLineFormatter implements FormatterInterface
{
    private $dataSeparatorStart = "[";
    private $dataSeparatorEnd = "]";

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
        $parsedContext = $this->parseContext($record['context']);

        return $this->dataSeparatorStart . $record['date'] . $this->dataSeparatorEnd
        . $this->dataSeparatorStart . $record['level'] . $this->dataSeparatorEnd
        . $this->dataSeparatorStart . $record['source'] . $this->dataSeparatorEnd
        . $this->dataSeparatorStart . $record['message'] . $this->dataSeparatorEnd
        . ($parsedContext ? $this->dataSeparatorStart . $parsedContext . $this->dataSeparatorEnd : '') . PHP_EOL;
    }

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
