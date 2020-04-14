<?php

declare(strict_types=1);

namespace flotzilla\Logger\Formatter;

use flotzilla\Logger\Exception\FormatterException;
use flotzilla\Logger\LogLevel\LogLevel;

class JsonFormatter implements FormatterInterface
{
    /** @var int $encodeOptions */
    protected $encodeOptions;

    /** @var int $defaultEncodeOptions */
    protected $defaultEncodeOptions = JSON_FORCE_OBJECT | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
    | JSON_PARTIAL_OUTPUT_ON_ERROR | JSON_PRESERVE_ZERO_FRACTION | JSON_THROW_ON_ERROR;

    /**
     * JsonFormatter constructor.
     * Pass predefined php json constants (as or argument)
     * @see https://www.php.net/manual/en/json.constants.php
     * @param int|null $encoderOptions
     */
    public function __construct(?int $encoderOptions = null)
    {
        $this->encodeOptions = $encoderOptions ?: $this->defaultEncodeOptions;
    }

    /**
     * @param string $message
     * @param array $context
     * @param string $level
     * @param string $date
     * @return string
     *
     * @throws FormatterException
     */
    public function format(
        string $message = '',
        array $context = [],
        string $level = LogLevel::DEBUG,
        string $date = ''
    ): string
    {
        $resultMessage = '';
        $jsonMessageArray = [
            'date' => $date,
            'message' => $message,
            'level' => strtoupper($level),
            'data' => $context
        ];

        try {
            $resultMessage = json_encode($jsonMessageArray, $this->encodeOptions);
        } catch (\JsonException $e) {
            throw new FormatterException('Json formatting error',
                $message,
                $context,
                $level,
                $date,
                $e
            );
        }

        return $resultMessage;
    }
}
