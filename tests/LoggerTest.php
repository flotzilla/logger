<?php

namespace flotzilla\Logger\Test;

use flotzilla\Logger\Channel\Channel;
use flotzilla\Logger\Exception\InvalidLogLevelException;
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

    public function testCreationMinMaxLogLevels()
    {
        $channels = [
            new Channel(
                'test',
                [
                    new FileHandler('test-main', 'tmp', new SimpleLineFormatter()),
                    new FileHandler('test-additional', 'tmp', new SimpleLineFormatter())
                ],
                LogLevel::DEBUG,
                LogLevel::CRITICAL
            )

        ];

        $logger = new Logger($channels);
        $logger->info("debug message");

        $this->assertTrue(file_exists('tmp'));
        $this->assertTrue(is_dir('tmp'));
        $this->assertTrue(is_writable('tmp'));
        $this->assertTrue(file_exists('tmp/test-main-' . date('j.n.Y') . '.log'));
        $this->assertTrue(file_exists('tmp/test-additional-' . date('j.n.Y') . '.log'));
    }

    public function testCreationMinMaxLogLevelsSkipLower()
    {
        $channels = [
            new Channel(
                'test',
                [
                    new FileHandler('test-main', 'tmp', new SimpleLineFormatter()),
                    new FileHandler('test-additional', 'tmp', new SimpleLineFormatter())
                ],
                LogLevel::DEBUG,
                LogLevel::ALERT
            )

        ];

        $logger = new Logger($channels);
        $logger->emergency("debug message");

        $this->assertTrue(file_exists('tmp'));
        $this->assertTrue(is_dir('tmp'));
        $this->assertTrue(is_writable('tmp'));
        $this->assertFalse(file_exists('tmp/test-main-' . date('j.n.Y') . '.log'));
        $this->assertFalse(file_exists('tmp/test-additional-' . date('j.n.Y') . '.log'));
    }

    public function testCreationMinMaxLogLevelsSkipUpper()
    {
        $channels = [
            new Channel(
                'test',
                [
                    new FileHandler('test-main', 'tmp', new SimpleLineFormatter()),
                    new FileHandler('test-additional', 'tmp', new SimpleLineFormatter())
                ],
                LogLevel::INFO,
                LogLevel::CRITICAL
            )

        ];

        $logger = new Logger($channels);
        $logger->debug("debug message");

        $this->assertTrue(file_exists('tmp'));
        $this->assertTrue(is_dir('tmp'));
        $this->assertTrue(is_writable('tmp'));
        $this->assertFalse(file_exists('tmp/test-main-' . date('j.n.Y') . '.log'));
        $this->assertFalse(file_exists('tmp/test-additional-' . date('j.n.Y') . '.log'));
    }

    public function testLogWithErrorLogLevel()
    {
        $channels = [
            new Channel(
                'test', [
                new FileHandler('test-main', 'tmp', new SimpleLineFormatter()),
                new FileHandler('test-additional', 'tmp', new SimpleLineFormatter())
            ], LogLevel::ERROR)
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
            'test', [
            new FileHandler('test-main', 'tmp', new SimpleLineFormatter()),
            new FileHandler('test-additional', 'tmp', new SimpleLineFormatter()),
        ], LogLevel::DEBUG
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

    public function testWithWrongLevel()
    {
        $this->expectException(InvalidLogLevelException::class);
        $this->expectExceptionMessage('some wrong level Log level is not exists');
        $logger = new Logger();
        $logger->log('some wrong level', 'test mess');
    }

    public function testGetEmptyChannels()
    {
        $logger = new Logger();
        $this->assertCount(0, $logger->getChannels());
    }

    public function testGetChannels()
    {
        $logger = new Logger(
            [
                new Channel('test-channel')
            ]
        );
        $this->assertCount(1, $logger->getChannels());
    }

    public function testGetChannelsFromSetter()
    {
        $channel = new Channel('test-channel');

        $logger = new Logger();
        $this->assertCount(0, $logger->getChannels());
        $logger->setChannels([$channel]);
        $this->assertCount(1, $logger->getChannels());
    }
}
