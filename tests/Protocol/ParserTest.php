<?php
namespace Air\FCgi\Test\Protocol;

use Air\FCgi\Exception\FastCGIException;
use Air\FCgi\Protocol\Parser;
use Air\FCgi\Protocol\Record\BeginRequestRecord;
use Air\FCgi\Protocol\Record\ParamsRecord;
use PHPUnit\Framework\TestCase;

class ParserTest extends TestCase
{
    public function testParsing(): void
    {
        //one begin request with two empty request
        $dataStream = hex2bin('0101000100080000000101000000000001040001000000000104000100000000');
        $bufferSize = strlen($dataStream);
        $this->assertEquals(32, $bufferSize);

        try {
            $error = '11111111';
            Parser::frame($error);
        } catch (FastCGIException $e) {
        }

        //begin request
        $record = Parser::frame($dataStream);
        $this->assertInstanceOf(BeginRequestRecord::class, $record);
        $recordSize = strlen((string) $record);
        $this->assertEquals(16, $recordSize);

        $this->assertEquals($bufferSize - $recordSize, strlen($dataStream));

        //first request
        $record = Parser::frame($dataStream);
        $this->assertInstanceOf(ParamsRecord::class, $record);

        //second request
        $record = Parser::frame($dataStream);
        $this->assertInstanceOf(ParamsRecord::class, $record);

        $this->assertEquals(0, strlen($dataStream));
    }
}
