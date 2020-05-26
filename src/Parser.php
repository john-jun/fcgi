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
class Parser
{
    /**
     * @var string[]
     */
    protected static $classMapping = [
        FastCGI::BEGIN_REQUEST => BeginRequestRecord::class,
        FastCGI::ABORT_REQUEST => AbortRequestRecord::class,
        FastCGI::END_REQUEST => EndRequestRecord::class,
        FastCGI::PARAMS => ParamsRecord::class,
        FastCGI::STDIN => StdinRecord::class,
        FastCGI::STDOUT => StdoutRecord::class,
        FastCGI::STDERR => StderrRecord::class,
        FastCGI::DATA => DataRecord::class,
        FastCGI::GET_VALUES => GetValuesRecord::class,
        FastCGI::GET_VALUES_RESULT => GetValuesResultRecord::class,
        FastCGI::UNKNOWN_TYPE => UnknownTypeRecord::class
    ];

    /**
     * @param array $header
     * @param string $buffer
     * @return Record
     */
    public static function parseFrame(array $header, string &$buffer): Record
    {
        $recordType = $header['type'];
        if (!isset(self::$classMapping[$recordType])) {
            throw new DomainException("Invalid FastCGI record type {$recordType} received");
        }

        /** @var Record $className */
        $className = self::$classMapping[$recordType];
        $record = $className::unpack($buffer, $header);

        $offset = FastCGI::HEADER_LEN + $record->getContentLength() + $record->getPaddingLength();
        $buffer = substr($buffer, $offset);

        return $record;
    }
}
