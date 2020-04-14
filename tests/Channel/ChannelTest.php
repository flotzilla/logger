<?php

namespace flotzilla\Logger\Test\Channel;

use flotzilla\Logger\Channel\Channel;
use flotzilla\Logger\Exception\InvalidChannelNameException;
use flotzilla\Logger\Exception\InvalidConfigurationException;
use flotzilla\Logger\Exception\InvalidLogLevelException;
use flotzilla\Logger\LogLevel\LogLevel;
use PHPUnit\Framework\TestCase;

class ChannelTest extends TestCase
{
    /** @var Channel */
    protected $channel;

    protected function setUp()
    {
        parent::setUp();
        $this->channel = new Channel('test-channel');
    }

    protected function tearDown()
    {
        // remove all files from directory
        system("rm -rf " . escapeshellarg('tmp'));;
    }

    public function testIsEnabled()
    {
        $this->assertTrue($this->channel->isEnabled());
    }

    public function testIsDisabled()
    {
        $this->channel->setEnabled(false);

        $this->assertFalse($this->channel->isEnabled());
    }

    public function testIncorrectConstructorMaxLevel()
    {
        $this->expectException(InvalidLogLevelException::class);
        $this->expectExceptionMessage("Invalid invalidMaxLevel max level parameter");
        $channel = new Channel('testError', [], 'invalidMaxLevel');
    }

    public function testIncorrectConstructorMinLevel()
    {
        $this->expectException(InvalidLogLevelException::class);
        $this->expectExceptionMessage("Invalid invalidMinLevel max level parameter");
        $channel = new Channel('testError', [], LogLevel::ALERT, 'invalidMinLevel');
    }

    public function testHandlersError()
    {
        $this->expectException(InvalidConfigurationException::class);
        $this->expectExceptionMessage("Array arguments should be instance of HandlerInterface");
        $channel = new Channel('testError', [new \stdClass()], LogLevel::ALERT, 'invalidMinLevel');
    }

    public function testGetChannelName()
    {
        $channel = new Channel('test name');
        $this->assertEquals('test name', $channel->getChannelName());
    }

    public function testGetMinMaxLogLevel()
    {
        $c = new Channel('test-channel');
        $this->assertEquals(LogLevel::DEBUG, $c->getMaxLogLevel());
        $this->assertEquals(LogLevel::EMERGENCY, $c->getMinLogLevel());
    }

    public function testSetMaxLogLevel()
    {
        $c = new Channel('test-channel');
        $this->assertEquals(LogLevel::DEBUG, $c->getMaxLogLevel());

        $c->setMaxLogLevel(LogLevel::INFO);
        $this->assertEquals(LogLevel::INFO, $c->getMaxLogLevel());
    }

    public function testSetMaxLogLevelTwice()
    {
        $c = new Channel('test-channel');
        $this->assertEquals(LogLevel::DEBUG, $c->getMaxLogLevel());

        $c->setMaxLogLevel(LogLevel::INFO);
        $this->assertEquals(LogLevel::INFO, $c->getMaxLogLevel());

        $c->setMaxLogLevel(LogLevel::ERROR);
        $this->assertEquals(LogLevel::ERROR, $c->getMaxLogLevel());
    }

    public function testSetMaxLogLevelException()
    {
        $this->expectException(InvalidLogLevelException::class);
        $this->expectExceptionMessage('Invalid some wrong level max level parameter');

        $c = new Channel('test-channel');
        $this->assertEquals(LogLevel::DEBUG, $c->getMaxLogLevel());

        $c->setMaxLogLevel('some wrong level');
    }

    public function testSetMinLogLevel()
    {
        $c = new Channel('test-channel');
        $this->assertEquals(LogLevel::EMERGENCY, $c->getMinLogLevel());

        $c->setMinLogLevel(LogLevel::ERROR);
        $this->assertEquals(LogLevel::ERROR, $c->getMinLogLevel());
    }

    public function testSetMinLogLevelTwice()
    {
        $c = new Channel('test-channel');
        $this->assertEquals(LogLevel::EMERGENCY, $c->getMinLogLevel());

        $c->setMinLogLevel(LogLevel::ERROR);
        $this->assertEquals(LogLevel::ERROR, $c->getMinLogLevel());

        $c->setMinLogLevel(LogLevel::INFO);
        $this->assertEquals(LogLevel::INFO, $c->getMinLogLevel());
    }

    public function testSetMinLogLevelException()
    {
        $this->expectException(InvalidLogLevelException::class);
        $this->expectExceptionMessage('Invalid some wrong level max level parameter');

        $c = new Channel('test-channel');
        $this->assertEquals(LogLevel::EMERGENCY, $c->getMinLogLevel());

        $c->setMinLogLevel('some wrong level');
    }

    public function testName()
    {
        $this->expectException(InvalidChannelNameException::class);
        $c = new Channel('');
    }
}
