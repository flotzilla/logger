<?php

namespace flotzilla\Logger\Test;

use flotzilla\Logger\Channel\Channel;
use flotzilla\Logger\Formatter\SimpleLineFormatter;
use flotzilla\Logger\Handler\FileHandler;
use flotzilla\Logger\Logger;
use PHPUnit\Framework\TestCase;

class LoggerTest extends TestCase
{

    public function testCreation()
    {
        $channels = [
            new Channel('test', [
                new FileHandler('test-main', 'tmp', new SimpleLineFormatter()),
                new FileHandler('test-additional', 'tmp', new SimpleLineFormatter())
            ])
        ];

        $logger = new Logger($channels);
        $logger->info("debug message");

        $this->assertTrue(file_exists('tmp'));
        $this->assertTrue(is_dir('tmp'));
        $this->assertTrue(is_writable('tmp'));
        $this->assertTrue(file_exists('tmp/test-main-' . date('j.n.Y') . '.log'));
        $this->assertTrue(file_exists('tmp/test-additional-' . date('j.n.Y') . '.log'));

    }
}