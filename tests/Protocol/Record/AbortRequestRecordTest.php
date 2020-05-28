<?php
namespace Air\FCgi\Test\Protocol\Record;

use Air\FCgi\Protocol\Constant;
use Air\FCgi\Protocol\Record\AbortRequestRecord;
use PHPUnit\Framework\TestCase;

class AbortRequestRecordTest extends TestCase
{
    protected static $rawMessage = '0102000100000000';

    public function testPacking(): void
    {
        $record = new AbortRequestRecord();
        $record->setRequestId(1);

        $this->assertEquals(Constant::ABORT_REQUEST, $record->getType());
        $this->assertEquals(1, $record->getRequestId());

        $this->assertSame(self::$rawMessage, bin2hex((string) $record));
    }

    public function testUnpacking(): void
    {
        $request = AbortRequestRecord::unpack(hex2bin(self::$rawMessage));
        $this->assertEquals(Constant::ABORT_REQUEST, $request->getType());
        $this->assertEquals(1, $request->getRequestId());
    }
}
