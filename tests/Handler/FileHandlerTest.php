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
        $handler = new FileHandler(new SimpleLineFormatter(), 'logs', 'test');

        $this->assertTrue($handler->checkAvailability());
    }

    public function testDirectoryRecursivePermission()
    {
        $handler = new FileHandler(new SimpleLineFormatter(), 'logs/test', 'test');

        $this->assertTrue($handler->checkAvailability());

        rmdir('logs/test');
    }

    public function testDirectoryAlreadyCreated()
    {
        mkdir('logs', 644);
        $handler = new FileHandler(new SimpleLineFormatter(), 'logs', 'test');

        $this->assertTrue($handler->checkAvailability());
    }

    public function testDirectoryPermissionError()
    {
        $this->expectException(InvalidConfigurationException::class);
        $this->expectExceptionMessage('Logging directory is not exists or is not writable');
        mkdir('logs', 111);
        $handler = new FileHandler(new SimpleLineFormatter(), 'logs', 'test');

        $this->assertTrue($handler->checkAvailability());
    }

    public function testHandleSuccess()
    {
        $file = 'logs/test-' . date("j.n.Y") . '.log';
        $handler = new FileHandler(new TestFormatter, 'logs', 'test');

        $this->assertFalse(file_exists($file));

        $handler->handle('test');

        $this->assertTrue(file_exists('logs'));
        $this->assertTrue(filesize($file) > 0);
    }

    public function testHandleSanitise()
    {
        $file = 'logs/test-' . date("j.n.Y") . '.log';
        $handler = new FileHandler(new TestFormatter, 'logs/', 'test');

        $this->assertFalse(file_exists($file));

        $handler->handle('test');

        $this->assertTrue(file_exists('logs'));
        $this->assertTrue(filesize($file) > 0);
    }

    public function testHandleSanitiseSubdir()
    {
        $file = 'logs/tmp/test-' . date("j.n.Y") . '.log';
        $handler = new FileHandler(new TestFormatter, 'logs/tmp', 'test');

        $this->assertFalse(file_exists($file));

        $handler->handle('test');

        $this->assertTrue(file_exists('logs'));
        $this->assertTrue(filesize($file) > 0);
    }

    public function testHandleSanitiseSubdirSlash()
    {
        $file = 'logs/tmp/test-' . date("j.n.Y") . '.log';
        $handler = new FileHandler(new TestFormatter, 'logs/tmp', 'test');

        $this->assertFalse(file_exists($file));

        $handler->handle('test');

        $this->assertTrue(file_exists('logs'));
        $this->assertTrue(filesize($file) > 0);
    }

    public function testCorruptAppendLog()
    {
        $file = 'logs/test-' . date("j.n.Y") . '.log';
        $handler = new FileHandler(new TestFormatter, 'logs', 'test');

        $this->assertFalse(file_exists($file));

        system("rm -rf ".escapeshellarg('logs'));
        $result = $handler->handle('test');
        $this->assertFalse($result);
    }

    public function testCreateWrongDateTimeFormat()
    {
        $this->expectException(InvalidConfigurationException::class);
        $this->expectExceptionMessage('Invalid datetime format');
        $handler = new FileHandler(new TestFormatter, 'logs', 'test', 'someDateTime');
    }
}