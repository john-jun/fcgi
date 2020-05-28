<?php
declare(strict_types=1);

namespace Air\FCgi\Protocol;

use Air\FCgi\Exception\FastCGIException;
use Air\FCgi\Protocol\Record\AbortRequestRecord;
use Air\FCgi\Protocol\Record\BeginRequestRecord;
use Air\FCgi\Protocol\Record\DataRecord;
use Air\FCgi\Protocol\Record\EndRequestRecord;
use Air\FCgi\Protocol\Record\GetValuesRecord;
use Air\FCgi\Protocol\Record\GetValuesResultRecord;
use Air\FCgi\Protocol\Record\ParamsRecord;
use Air\FCgi\Protocol\Record\StderrRecord;
use Air\FCgi\Protocol\Record\StdinRecord;
use Air\FCgi\Protocol\Record\StdoutRecord;
use Air\FCgi\Protocol\Record\UnknownTypeRecord;

/**
 * Class FrameParser
 * @package Air\FCgi
 */
class Parser
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
     * @return Record
     * @throws FastCGIException
     */
    public static function frame(string &$buffer): Record
    {
        $header = unpack(Constant::HEADER_FORMAT, $buffer);
        $recordType = $header['type'];
        if (!isset(self::$classMapping[$recordType])) {
            throw new FastCGIException("Invalid FastCGI record type {$recordType} received");
        }

        /** @var Record $className */
        $className = self::$classMapping[$recordType];
        $record = $className::unpack($buffer, $header);

        $offset = Constant::HEADER_LEN + $record->getContentLength() + $record->getPaddingLength();
        $buffer = substr($buffer, $offset);

        return $record;
    }
}
