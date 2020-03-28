<?php


use flotzilla\Logger\Channel\Channel;
use flotzilla\Logger\Formatter\PsrFormatter;
use flotzilla\Logger\Formatter\SimpleLineFormatter;
use flotzilla\Logger\Handler\FileHandler;
use flotzilla\Logger\Logger;

/**
 * Class LoggerBench
 * @BeforeMethods({"init"})
 * @AfterClassMethods({"after"})
 */
class LoggerBench
{
    protected $logger;

    public function init()
    {
        $channels = [
            new Channel('test', [
                new FileHandler('test-simple-line', 'tmp', new SimpleLineFormatter()),
                new FileHandler('test-psr-formatter', 'tmp', new PsrFormatter())
            ])
        ];

        $this->logger = new Logger($channels);
    }
    public static function after()
    {
        system("rm -rf " . escapeshellarg('tmp'));;
    }

    /**
     * @Revs(5)
     * @Iterations(10)
     */
    public function benchLog()
    {
        $this->logger->info('test message');
    }

    /**
     * @Iterations(5)
     */
    public function benchInsert()
    {
        foreach (range(0, 100000) as $iteration) {
            $this->logger->info('test message' . rand($iteration) . 21 . 'str');
        }
    }
}