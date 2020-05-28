<?php
namespace Air\FCgi\Test\Protocol;

use Air\FCgi\Protocol\Constant;
use Air\FCgi\Protocol\Record;
use PHPUnit\Framework\TestCase;

class RecordTest extends TestCase
{
    public function testUnpackingPacket()
    {
        $packet = hex2bin('01010001000800000001010000000000');
        $record = Record::unpack($packet);

        //Verify all general fields
        $this->assertEquals(Constant::VERSION_1, $record->getVersion());
        $this->assertEquals(Constant::BEGIN_REQUEST, $record->getType());
        $this->assertEquals(1, $record->getRequestId());
        $this->assertEquals(8, $record->getContentLength());
        $this->assertEquals(0, $record->getPaddingLength());

        //Check payload data
        $this->assertEquals($record->getContentData(), hex2bin('0001010000000000'));
    }

    public function testPackingPacket()
    {
        $record = new Record();
        $record->setRequestId(5);
        $record->setContentData('test');
        $packet = (string) $record;

        $this->assertEquals($packet, hex2bin('010b0005000404007465737400000000'));
        $result = Record::unpack($packet);

        $this->assertEquals(Constant::UNKNOWN_TYPE, $result->getType());
        $this->assertEquals(5, $result->getRequestId());
        $this->assertEquals('test', $result->getContentData());
    }

    public function testAutomaticCalculationOfPaddingLength()
    {
        $record = new Record();

        $record->setContentData('test');
        $this->assertEquals(4, $record->getPaddingLength());

        $record->setContentData('test_test');
        $this->assertEquals(7, $record->getPaddingLength());
    }
}
