<?php

namespace flotzilla\Logger\Test\Channel;

use flotzilla\Logger\Channel\Channel;
use flotzilla\Logger\Exception\InvalidLogLevelException;
use flotzilla\Logger\Formatter\SimpleLineFormatter;
use flotzilla\Logger\Handler\FileHandler;
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

    public function testSetHandlers()
    {
        $handler1 = new FileHandler('test-main', 'tmp', new SimpleLineFormatter());
        $handler2 = new FileHandler('test-additional', 'tmp', new SimpleLineFormatter());
        $handlers = [$handler1, $handler2];
        $this->channel->setHandlers($handlers);
        $this->assertCount(2, $this->channel->getHandlers());
        $this->assertInstanceOf(FileHandler::class, $this->channel->getHandlers()[0]);
        $this->assertInstanceOf(FileHandler::class, $this->channel->getHandlers()[1]);
    }

    public function testAddHandlers()
    {
        $handler1 = new FileHandler('test-main', 'tmp', new SimpleLineFormatter());
        $handler2 = new FileHandler('test-additional', 'tmp', new SimpleLineFormatter());

        $this->assertCount(0, $this->channel->getHandlers());

        $this->channel->addHandler($handler1);
        $this->assertCount(1, $this->channel->getHandlers());
        $this->channel->addHandler($handler2);
        $this->assertCount(2, $this->channel->getHandlers());
    }

    public function testAddHandlerByName()
    {
        $handler1 = new FileHandler('test-main', 'tmp', new SimpleLineFormatter());
        $handler2 = new FileHandler('test-additional', 'tmp', new SimpleLineFormatter());

        $this->assertCount(0, $this->channel->getHandlers());

        $this->channel->addHandler($handler1, 'test-main-handler');
        $this->assertCount(1, $this->channel->getHandlers());
        $this->channel->addHandler($handler2, 'test-additional-handler');
        $this->assertCount(2, $this->channel->getHandlers());

        $this->assertInstanceOf(FileHandler::class, $this->channel->getHandlers()['test-main-handler']);
        $this->assertInstanceOf(FileHandler::class, $this->channel->getHandlers()['test-additional-handler']);
    }

    public function testGetChannelName()
    {
        $channel = new Channel('test name');
        $this->assertEquals('test name', $channel->getChannelName());
    }

    // TODO finish this
}