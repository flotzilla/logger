<?php

declare(strict_types=1);

namespace flotzilla\Logger\Handler;

use flotzilla\Logger\Exception\InvalidConfigurationException;
use flotzilla\Logger\Formatter\FormatterInterface;

class FileHandler implements HandlerInterface
{
    /** @var string $handlerName */
    private $handlerName;

    /** @var string $logDir */
    private $logDir;

    /** @var FormatterInterface $formatter */
    protected $formatter;

    /**
     * FileHandler constructor.
     * @param string $handlerName
     * @param string $logDir
     * @param FormatterInterface|null $formatter
     * @throws InvalidConfigurationException
     */
    public function __construct(string $handlerName, string $logDir = "/tmp", FormatterInterface $formatter = null)
    {
        $this->handlerName = $handlerName;
        $this->logDir = $logDir;
        $this->formatter = $formatter;

        $this->makeLogDirectory();

        if (!$this->checkAvailability()) {
            throw new InvalidConfigurationException("Logging directory is not exists or is not writable");
        }
    }

    /**
     * @param array $record
     * @return bool
     * @throws InvalidConfigurationException
     */
    public function handle(array $record): bool
    {
        if (!$this->formatter) {
            throw new InvalidConfigurationException("Default Logger formatter is not initialized");
        }

        return $this->appendLog(
            $this->formatter->format($record)
        );
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
     * @param string $log
     * @return bool
     */
    private function appendLog(string $log): bool
    {
        // TODO date format for us countries
        $result = file_put_contents(
            $this->pathSanitize($this->logDir) . DIRECTORY_SEPARATOR
            . $this->handlerName . '-' . date("j.n.Y") . '.log',
            $log,
            FILE_APPEND
        );

        return $result !== false;
    }

    private function pathSanitize(string $path): string
    {
        if ($path === '/'){
            return $path;
        }

        // found slash at end
        if (strpos($path, '/', strlen($path) - 1)){
            return substr($path, 0, strlen($path) - 1);
        }

        return $path;
    }
}
