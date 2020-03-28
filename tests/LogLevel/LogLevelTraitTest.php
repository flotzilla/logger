<?php

namespace flotzilla\Logger\Test\LogLevel;

use flotzilla\Logger\LogLevel\LogLevel;
use flotzilla\Logger\LogLevel\LogLevelTrait;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class LogLevelTraitTest extends TestCase
{
    /**
     * @var MockObject
     */
    protected $logLevelMock;

    protected function setUp()
    {
        $this->logLevelMock = $this->getMockForTrait(LogLevelTrait::class);
    }

    protected function tearDown()
    {
        $this->logLevelMock = null;
    }

    public function testIsLogLevelValid()
    {
        $this->assertTrue($this->logLevelMock->isLogLevelValid(LogLevel::CRITICAL));
    }

    public function testIsLogLevelInvalid()
    {
        $this->assertFalse($this->logLevelMock->isLogLevelValid('wrong log level'));
    }

    // TODO finish this
}
