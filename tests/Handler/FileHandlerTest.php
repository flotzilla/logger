<?php

namespace flotzilla\Logger\Test\Handler;

use flotzilla\Logger\Exception\InvalidConfigurationException;
use flotzilla\Logger\Formatter\SimpleLineFormatter;
use flotzilla\Logger\Handler\FileHandler;
use flotzilla\Logger\Test\Formatter\TestFormatter;
use PHPUnit\Framework\TestCase;

class FileHandlerTest extends TestCase
{
    protected function tearDown()
    {
        // remove all files from directory
        system("rm -rf ".escapeshellarg('logs'));;
    }

    public function testDirectoryPermission()
    {
        $handler = new FileHandler('test', 'logs', new SimpleLineFormatter());

        $this->assertTrue($handler->checkAvailability());
    }

    public function testDirectoryRecursivePermission()
    {
        $handler = new FileHandler('test', 'logs/test', new SimpleLineFormatter());

        $this->assertTrue($handler->checkAvailability());

        rmdir('logs/test');
    }

    public function testDirectoryAlreadyCreated()
    {
        mkdir('logs', 644);
        $handler = new FileHandler('test', 'logs', new SimpleLineFormatter());

        $this->assertTrue($handler->checkAvailability());
    }

    public function testDirectoryPermissionError()
    {
        $this->expectException(InvalidConfigurationException::class);
        $this->expectExceptionMessage('Logging directory is not exists or is not writable');
        mkdir('logs', 111);
        $handler = new FileHandler('test', 'logs', new SimpleLineFormatter());

        $this->assertTrue($handler->checkAvailability());
    }

    public function testHandleEmptyFormatter()
    {
        $this->expectException(InvalidConfigurationException::class);
        $this->expectExceptionMessage("Default Logger formatter is not initialized");

        $handler = new FileHandler('test', 'logs');
        $handler->handle([]);
    }

    public function testHandleSuccess()
    {
        $file = 'logs/test-' . date("j.n.Y") . '.log';
        $handler = new FileHandler('test', 'logs', new TestFormatter);
        $record = [ 'message' => 'test'];

        $this->assertFalse(file_exists($file));

        $handler->handle($record);

        $this->assertTrue(file_exists('logs'));
        $this->assertTrue(filesize($file) > 0);
    }

    public function testHandleSanitise()
    {
        $file = 'logs/test-' . date("j.n.Y") . '.log';
        $handler = new FileHandler('test', 'logs/', new TestFormatter);
        $record = [ 'message' => 'test'];

        $this->assertFalse(file_exists($file));

        $handler->handle($record);

        $this->assertTrue(file_exists('logs'));
        $this->assertTrue(filesize($file) > 0);
    }

    public function testHandleSanitiseSubdir()
    {
        $file = 'logs/tmp/test-' . date("j.n.Y") . '.log';
        $handler = new FileHandler('test', 'logs/tmp', new TestFormatter);
        $record = [ 'message' => 'test'];

        $this->assertFalse(file_exists($file));

        $handler->handle($record);

        $this->assertTrue(file_exists('logs'));
        $this->assertTrue(filesize($file) > 0);
    }

    public function testHandleSanitiseSubdirSlash()
    {
        $file = 'logs/tmp/test-' . date("j.n.Y") . '.log';
        $handler = new FileHandler('test', 'logs/tmp/', new TestFormatter);
        $record = [ 'message' => 'test'];

        $this->assertFalse(file_exists($file));

        $handler->handle($record);

        $this->assertTrue(file_exists('logs'));
        $this->assertTrue(filesize($file) > 0);
    }

    public function testCorruptAppendLog()
    {
        $file = 'logs/test-' . date("j.n.Y") . '.log';
        $handler = new FileHandler('test', 'logs', new TestFormatter);
        $record = [ 'message' => 'test'];

        $this->assertFalse(file_exists($file));

        system("rm -rf ".escapeshellarg('logs'));
        $result = $handler->handle($record);
        $this->assertFalse($result);
    }
}