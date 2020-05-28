<?php
namespace Air\FCgi\Test\Protocol;

use Air\FCgi\Exception\FastCGIException;
use Air\FCgi\Protocol\Record\EndRequestRecord;
use Air\FCgi\Protocol\Record\StderrRecord;
use Air\FCgi\Protocol\Record\StdoutRecord;
use Air\FCgi\Protocol\Response;
use PHPUnit\Framework\TestCase;

class ResponseTest extends TestCase
{
    public function testResponse()
    {
        $this->expectException(FastCGIException::class);
        new Response([]);
        new Response([new StderrRecord()]);

        $response = new Response([
            new StdoutRecord('test'),
            new EndRequestRecord()
        ]);

        $this->assertEquals(1, $response->getRequestId());
        $this->assertEquals('test', $response->getBody());
        $this->assertEquals('', $response->getError());
    }
}