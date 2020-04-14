<?php

declare(strict_types=1);

namespace flotzilla\Logger\Test\Helper;

use flotzilla\Logger\Helper\Helper;
use PHPUnit\Framework\TestCase;

class HelperTest extends TestCase
{
    /**
     * @dataProvider stringFormatDataProviderSuccess
     * @param string $format
     */
    public function testIsTimeFormatValid(string $format)
    {
        $resp = Helper::isTimeFormatValid($format);
        $this->assertTrue($resp);
    }

    /**
     * @param $format
     * @dataProvider stringFormatDataProviderError
     */
    public function testInvalidFormat($format)
    {
        $resp = Helper::isTimeFormatValid($format);
        $this->assertFalse($resp);
    }

    public function stringFormatDataProviderSuccess()
    {
        return [
            ['d.m.y'],
            ['j.n.Y'],
            ['Y.j.m-h:i:s.u']
        ];
    }

    public function stringFormatDataProviderError()
    {
        return [
            ['some str'],
            ['a-a-a-a-a']
        ];
    }
}
