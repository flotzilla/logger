<?php

namespace flotzilla\Logger\Test\Handler;

use flotzilla\Logger\Exception\InvalidConfigurationException;
use flotzilla\Logger\Formatter\SimpleLineFormatter;
use flotzilla\Logger\Handler\FileHandler;
use PHPUnit\Framework\TestCase;

class FileHandlerTest extends TestCase
{
    protected function tearDown()
    {
        rmdir('logs');
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

//    public function testSanitise()
//    {
//        $handler = new FileHandler('test/', 'logs', new SimpleLineFormatter());
//
//        $this->assertEquals('test', $handler->pathSanitize('test'));
//        $this->assertEquals('test', $handler->pathSanitize('test/'));
//        $this->assertEquals('/tmp/test', $handler->pathSanitize('/tmp/test/'));
//        $this->assertEquals('/', $handler->pathSanitize('/'));
//    }
}