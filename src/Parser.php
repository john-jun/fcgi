<?php
declare(strict_types=1);

namespace Air\FCgi;

use Air\FCgi\Exception\FastCGIException;
use Air\FCgi\Record\AbortRequestRecord;
use Air\FCgi\Record\AbstractRecord;
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
        FastCGIConstant::BEGIN_REQUEST => BeginRequestRecord::class,
        FastCGIConstant::ABORT_REQUEST => AbortRequestRecord::class,
        FastCGIConstant::END_REQUEST => EndRequestRecord::class,
        FastCGIConstant::PARAMS => ParamsRecord::class,
        FastCGIConstant::STDIN => StdinRecord::class,
        FastCGIConstant::STDOUT => StdoutRecord::class,
        FastCGIConstant::STDERR => StderrRecord::class,
        FastCGIConstant::DATA => DataRecord::class,
        FastCGIConstant::GET_VALUES => GetValuesRecord::class,
        FastCGIConstant::GET_VALUES_RESULT => GetValuesResultRecord::class,
        FastCGIConstant::UNKNOWN_TYPE => UnknownTypeRecord::class
    ];

    /**
     * @param string $buffer
     * @return AbstractRecord
     * @throws FastCGIException
     */
    public static function frame(string &$buffer): AbstractRecord
    {
        $header = unpack(FastCGIConstant::HEADER_FORMAT, $buffer);
        $recordType = $header['type'];
        if (!isset(self::$classMapping[$recordType])) {
            throw new FastCGIException("Invalid FastCGI record type {$recordType} received");
        }

        /** @var AbstractRecord $className */
        $className = self::$classMapping[$recordType];
        $record = $className::unpack($buffer, $header);

        $offset = FastCGIConstant::HEADER_LEN + $record->getContentLength() + $record->getPaddingLength();
        $buffer = substr($buffer, $offset);

        return $record;
    }
}
