<?php


namespace flotzilla\Logger\Test;


use flotzilla\Logger\Formatter\SimpleLineFormatter;
use flotzilla\Logger\Handler\FileHandler;
use flotzilla\Logger\Logger;
use PHPUnit\Framework\TestCase;

class LoggerTest extends TestCase
{

    public function testCreationSuccess()
    {
        $handlers = [
            new FileHandler("main", "/tmp", new SimpleLineFormatter()),
            new FileHandler("additional", "/tmp", new SimpleLineFormatter())
        ];

        $logger = new Logger('main', $handlers);
        $logger->info("ololo");


        $this->assertTrue(file_exists("/tmp/main-" . date("j.n.Y") . '.log'));
        $this->assertTrue(file_exists("/tmp/additional-" . date("j.n.Y") . '.log'));
    }
}