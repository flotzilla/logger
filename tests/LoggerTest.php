<?php

namespace flotzilla\Logger\Test;

use flotzilla\Logger\Channel\Channel;
use flotzilla\Logger\Formatter\SimpleLineFormatter;
use flotzilla\Logger\Handler\FileHandler;
use flotzilla\Logger\Logger;
use flotzilla\Logger\LogLevel\LogLevel;
use PHPUnit\Framework\TestCase;

class LoggerTest extends TestCase
{
    protected function tearDown()
    {
        // remove all files from directory
        system("rm -rf " . escapeshellarg('tmp'));;
    }

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

    public function testLogWithErrorLogLevel()
    {
        $channels = [
            new Channel(
                'test',
                [
                    new FileHandler('test-main', 'tmp', new SimpleLineFormatter()),
                    new FileHandler('test-additional', 'tmp', new SimpleLineFormatter())
                ],
                LogLevel::ERROR)
        ];

        $logger = new Logger($channels);
        $logger->info("debug message");

        $this->assertTrue(file_exists('tmp'));
        $this->assertTrue(is_dir('tmp'));
        $this->assertTrue(is_writable('tmp'));
        $this->assertFalse(file_exists('tmp/test-main-' . date('j.n.Y') . '.log'));
        $this->assertFalse(file_exists('tmp/test-additional-' . date('j.n.Y') . '.log'));
    }

    public function testLogWithDisabledChannel()
    {
        $channel = new Channel
        (
            'test',
            [
                new FileHandler('test-main', 'tmp', new SimpleLineFormatter()),
                new FileHandler('test-additional', 'tmp', new SimpleLineFormatter()),
            ],
            LogLevel::DEBUG
        );

        $channel->setEnabled(false);
        $channels = [ $channel];

        $logger = new Logger($channels);
        $logger->info("debug message");

        $this->assertTrue(file_exists('tmp'));
        $this->assertTrue(is_dir('tmp'));
        $this->assertTrue(is_writable('tmp'));

        $this->assertFalse($channel->isEnabled());
        $this->assertFalse(file_exists('tmp/test-main-' . date('j.n.Y') . '.log'));
        $this->assertFalse(file_exists('tmp/test-additional-' . date('j.n.Y') . '.log'));

    }
}