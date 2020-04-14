<?php

namespace flotzilla\Logger\Test;

use flotzilla\Logger\Channel\Channel;
use flotzilla\Logger\Exception\FormatterException;
use flotzilla\Logger\Exception\InvalidConfigurationException;
use flotzilla\Logger\Exception\InvalidLogLevelException;
use flotzilla\Logger\Exception\LoggerErrorStackException;
use flotzilla\Logger\Formatter\JsonFormatter;
use flotzilla\Logger\Formatter\PsrFormatter;
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
                new FileHandler(new SimpleLineFormatter(), 'tmp', 'test-main'),
                new FileHandler(new SimpleLineFormatter(), 'tmp', 'test-additional')
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

    public function testLogWithContext()
    {
        $channels = [
            new Channel('test', [
                new FileHandler(new PsrFormatter(), 'tmp', 'test-main'),
                new FileHandler(new SimpleLineFormatter(), 'tmp', 'test-additional')
            ])
        ];

        $logger = new Logger($channels);

        $testObj = new TestClass();
        $logger->info("debug message", ['some var']);
        $logger->info("debug message", ['key' => 'val', 'key1' => 'val1']);
        $logger->info("debug message", ['key' => 'val', [], new \stdClass()]);
        $logger->info("debug message", [$testObj]);

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
                    new FileHandler(new SimpleLineFormatter(), 'tmp', 'test-main'),
                    new FileHandler(new SimpleLineFormatter(), 'tmp', 'test-additional')
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
                    new FileHandler(new SimpleLineFormatter(), 'tmp', 'test-main'),
                    new FileHandler(new SimpleLineFormatter(), 'tmp', 'test-additional')
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
                    new FileHandler(new SimpleLineFormatter(), 'tmp', 'test-main'),
                    new FileHandler(new SimpleLineFormatter(), 'tmp', 'test-additional')
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
                new FileHandler(new SimpleLineFormatter(), 'tmp', 'test-main'),
                new FileHandler(new SimpleLineFormatter(), 'tmp', 'test-additional')
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
            new FileHandler(new SimpleLineFormatter(), 'tmp', 'test-main'),
            new FileHandler(new SimpleLineFormatter(), 'tmp', 'test-additional')
        ], LogLevel::DEBUG
        );

        $channel->setEnabled(false);
        $channels = [$channel];

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
        $this->expectExceptionMessage('some wrong level Log level is not exist');
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

    public function testInvalidDateFormat()
    {
        $this->expectException(InvalidConfigurationException::class);
        $this->expectExceptionMessage('Invalid date time format');
        $channel = new Channel('test-channel');

        $logger = new Logger(
            [
                $channel
            ],
            'wrong dateTime format'
        );
    }

    public function testThrowMultipleExceptions()
    {
        $jsonFormatterMock = $this->createMock(JsonFormatter::class);
        $jsonFormatterMock->method('format')
            ->willThrowException(new FormatterException);

        $simpleFormatterMock = $this->createMock(SimpleLineFormatter::class);
        $simpleFormatterMock->method('format')
            ->willThrowException(new FormatterException);

        $channels = [
            new Channel('test',
                [
                    new FileHandler($simpleFormatterMock, 'tmp', 'test-main'),
                    new FileHandler($jsonFormatterMock, 'tmp', 'test-additional')
                ])
        ];
        $logger = new Logger($channels);
        try {
            $logger->info('test message', ['some_data' => ['text' => 'sss']]);
        } catch (LoggerErrorStackException $e) {
            $this->assertCount(2, $e->count());
        }
    }

    public function testThrowOneError()
    {
        $simpleFormatterMock = $this->createMock(SimpleLineFormatter::class);
        $simpleFormatterMock->method('format')
            ->willThrowException(new FormatterException);

        $channels = [
            new Channel('test',
                [
                    new FileHandler($simpleFormatterMock, 'tmp', 'test-main'),
                    new FileHandler(new JsonFormatter(), 'tmp', 'test-additional')
                ])
        ];
        $logger = new Logger($channels);
        try {
            $logger->info('test message', ['some_data' => ['text' => 'sss']]);

        } catch (LoggerErrorStackException $e) {
            $this->assertCount(1, $e->count());
            $this->assertTrue(file_exists('tmp/test-additional-' . date('j.n.Y') . '.log'));
        }
    }
}