<?php
declare(strict_types=1);

namespace Air\FCgi;

use Air\FCgi\Record\AbortRequestRecord;
use Air\FCgi\Record\BeginRequestRecord;
use Air\FCgi\Record\DataRecord;
use Air\FCgi\Record\EndRequestRecord;
use Air\FCgi\Record\GetValuesRecord;
use Air\FCgi\Record\GetValuesResultRecord;
use Air\FCgi\Record\ParamsRecord;
use Air\FCgi\Record\StderrRecord;
use Air\FCgi\Record\StdinRecord;
use Air\FCgi\Record\StdoutRecord;
use Air\FCgi\Record\UnknownTypeRecord;
use DomainException;
use RuntimeException;

/**
 * Class FrameParser
 * @package Air\FCgi
 */
class FrameParser
{
    /**
     * @var string[]
     */
    protected static $classMapping = [
        Constant::BEGIN_REQUEST => BeginRequestRecord::class,
        Constant::ABORT_REQUEST => AbortRequestRecord::class,
        Constant::END_REQUEST => EndRequestRecord::class,
        Constant::PARAMS => ParamsRecord::class,
        Constant::STDIN => StdinRecord::class,
        Constant::STDOUT => StdoutRecord::class,
        Constant::STDERR => StderrRecord::class,
        Constant::DATA => DataRecord::class,
        Constant::GET_VALUES => GetValuesRecord::class,
        Constant::GET_VALUES_RESULT => GetValuesResultRecord::class,
        Constant::UNKNOWN_TYPE => UnknownTypeRecord::class
    ];

    /**
     * @param string $buffer
     * @return bool
     */
    public static function hasFrame(string $buffer): bool
    {
        $bufferLength = strlen($buffer);
        if ($bufferLength < Constant::HEADER_LEN) {
            return false;
        }

        $fastInfo = unpack(Constant::HEADER_FORMAT, $buffer);
        if ($bufferLength < Constant::HEADER_LEN + $fastInfo['contentLength'] + $fastInfo['paddingLength']) {
            return false;
        }

        return true;
    }

    /**
     * @param string $buffer
     * @return Record
     */
    public static function parseFrame(string &$buffer): Record
    {
        $bufferLength = strlen($buffer);
        if ($bufferLength < Constant::HEADER_LEN) {
            throw new RuntimeException('Not enough data in the buffer to parse');
        }

        $recordHeader = unpack(Constant::HEADER_FORMAT, $buffer);
        $recordType = $recordHeader['type'];
        if (!isset(self::$classMapping[$recordType])) {
            throw new DomainException("Invalid FastCGI record type {$recordType} received");
        }

        /** @var Record $className */
        $className = self::$classMapping[$recordType];
        $record = $className::unpack($buffer);

        $offset = Constant::HEADER_LEN + $record->getContentLength() + $record->getPaddingLength();
        $buffer = substr($buffer, $offset);

        return $record;
    }
}
