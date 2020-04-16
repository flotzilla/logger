<?php

declare(strict_types=1);

namespace flotzilla\Logger\Handler;

use flotzilla\Logger\Exception\FormatterException;
use flotzilla\Logger\Exception\HandlerException;
use flotzilla\Logger\Exception\InvalidConfigurationException;
use flotzilla\Logger\Formatter\FormatterInterface;
use flotzilla\Logger\Helper\Helper;
use flotzilla\Logger\LogLevel\LogLevel;

class FileHandler implements HandlerInterface
{
    /** @var string $handlerName */
    protected $handlerName;

    /** @var string $fileNameDateFormat */
    protected $fileNameDateFormat;

    /** @var string $logDir */
    protected $logDir;

    /** @var FormatterInterface $formatter */
    protected $formatter;

    /** @var string $fileName */
    protected $fileName;

    /**
     * FileHandler constructor.
     * @param FormatterInterface $formatter
     * @param string $logDir directory name for saving logs
     * @param string $handlerName handler name for appending to file name
     * @param string $fileNameDateFormat date() format arguments for appending to file name
     * @throws InvalidConfigurationException
     */
    public function __construct(
        FormatterInterface $formatter,
        string $logDir = '/tmp',
        string $handlerName = '',
        string $fileNameDateFormat = 'j.n.Y'
    )
    {
        if (!Helper::isTimeFormatValid($fileNameDateFormat)) {
            throw new InvalidConfigurationException('Invalid datetime format');
        }

        $this->handlerName = $handlerName;
        $this->fileNameDateFormat = $fileNameDateFormat;
        $this->logDir = $logDir;
        $this->formatter = $formatter;

        $this->makeLogDirectory();

        if (!$this->checkAvailability()) {
            throw new InvalidConfigurationException('Logging directory is not exists or is not writable');
        }

        $this->fileName = $this->pathSanitise($this->logDir) . DIRECTORY_SEPARATOR
            . ($this->handlerName ? $this->handlerName . '-' : '') . date($this->fileNameDateFormat) . '.log';
    }

    /**
     * Write record to file
     *
     * @param string $message
     * @param array $context
     * @param string $level
     * @param string $date
     * @return bool operation success status
     *
     * @throws FormatterException
     * @throws HandlerException
     */
    public function handle(
        string $message = '',
        array $context = [],
        string $level = LogLevel::DEBUG,
        string $date = ''
    ): bool
    {
        $formattedMessage = $this->formatter->format($message, $context, $level, $date);
        if (!$isSuccess = $this->appendLog($formattedMessage . PHP_EOL)) {
            throw new HandlerException('Error during witting log message to file', $message, $context, $level, $date);
        }

        return $isSuccess;
    }

    /**
     * Check if logging directory available for witting
     * @return bool
     */
    public function checkAvailability(): bool
    {
        return is_dir($this->logDir) && is_writable($this->logDir);
    }

    /**
     * Create logging directory, if there is no one yet
     *
     * @return void
     */
    private function makeLogDirectory()
    {
        if (!is_dir($this->logDir)) {
            mkdir($this->logDir, 0755, true);
        }
    }

    /**
     * Append content to log file
     * If log directory does not exist will return false.
     * If content cannot be written to file or file cannot be opened will return false
     *
     * @param string $log string to be written to file
     * @return bool operation success status
     */
    private function appendLog(string $log): bool
    {
        $result = false;

        // directory permissions can be changed after initialization of this object
        if (!$this->checkAvailability()) {
            return $result;
        }

        if ($file = fopen($this->fileName, 'a')) {
            $result = fwrite($file, $log) !== false;
        }

        fclose($file);

        return $result;
    }

    /**
     * Remove slashes from end of the path string
     *
     * @param string $path string to be sanitised
     * @return string sanitised string
     */
    private function pathSanitise(string $path): string
    {
        if ($path === '/') {
            return $path;
        }

        // found slash at end
        if (strpos($path, '/', strlen($path) - 1)) {
            return substr($path, 0, -1);
        }

        return $path;
    }
}
