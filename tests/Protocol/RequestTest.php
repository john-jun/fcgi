<?php
namespace Air\FCgi\Test\Protocol;

use Air\FCgi\Protocol\Request;
use PHPUnit\Framework\TestCase;

class RequestTest extends TestCase
{
    public function testRequest()
    {
        $request = new Request(1, true);

        $this->assertEquals(1, $request->getRequestId());
        $this->assertEquals(1, $request->getKeepConn());

        $record = (string)$request;
        $this->assertEquals('01010001000800000001010000000000', bin2hex($record));
        $this->assertNotEquals('test', $record);
    }
}
