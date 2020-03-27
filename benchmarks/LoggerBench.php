<?php


use flotzilla\Logger\Channel\Channel;
use flotzilla\Logger\Formatter\PsrFormatter;
use flotzilla\Logger\Formatter\SimpleLineFormatter;
use flotzilla\Logger\Handler\FileHandler;
use flotzilla\Logger\Logger;

class LoggerBench
{
    /**
     * @Revs(5)
     * @Iterations(10)
     * @throws \flotzilla\Logger\Exception\InvalidConfigurationException
     */
    public function benchLog()
    {
        $channels = [
            new Channel('test', [
                new FileHandler('test-simple-line', 'tmp', new SimpleLineFormatter()),
                new FileHandler('test-psr-formatter', 'tmp', new PsrFormatter())
            ])
        ];

        $logger = new Logger($channels);
        $logger->info('test message');
    }

    public function benchInsert()
    {
        $channels = [
            new Channel('test', [
                new FileHandler('test-simple-line', 'tmp', new SimpleLineFormatter()),
                new FileHandler('test-psr-formatter', 'tmp', new PsrFormatter())
            ])
        ];

        $logger = new Logger($channels);
        foreach (range(0, 100000) as $iteration) {
            $logger->info('test message' . rand($iteration));
        }
    }
}