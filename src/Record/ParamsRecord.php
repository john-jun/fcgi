<?php
declare(strict_types=1);

namespace Air\FCgi\Record;

use Air\FCgi\FastCGIConstant;

/**
 * Class ParamsRecord
 * @package Air\FCgi\Record
 */
class ParamsRecord extends AbstractRecord
{
    /**
     * @var array
     */
    protected $values = [];

    /**
     * ParamsRecord constructor.
     * @param array $values
     */
    public function __construct(array $values = [])
    {
        $this->type = FastCGIConstant::PARAMS;
        $this->values = $values;

        $this->setContentData($this->packPayload());
    }

    /**
     * @return array
     */
    public function getValues(): array
    {
        return $this->values;
    }

    /**
     * @param $self
     * @param string $data
     */
    protected static function unpackPayload($self, string $data): void
    {
        $currentOffset = 0;

        do {
            [$nameLengthHigh] = array_values(unpack('CnameLengthHigh', $data));
            $isLongName = ($nameLengthHigh >> 7 == 1);
            $valueOffset = $isLongName ? 4 : 1;

            [$valueLengthHigh] = array_values(unpack('CvalueLengthHigh', substr($data, $valueOffset)));
            $isLongValue = ($valueLengthHigh >> 7 == 1);
            $dataOffset = $valueOffset + ($isLongValue ? 4 : 1);

            $formatParts = [
                $isLongName ? 'NnameLength' : 'CnameLength',
                $isLongValue ? 'NvalueLength' : 'CvalueLength',
            ];
            $format = join('/', $formatParts);
            [$nameLength, $valueLength] = array_values(unpack($format, $data));

            $nameLength &= ($isLongName ? 0x7fffffff : 0x7f);
            $valueLength &= ($isLongValue ? 0x7fffffff : 0x7f);
            [$nameData, $valueData] = array_values(
                unpack(
                    "a{$nameLength}nameData/a{$valueLength}valueData",
                    substr($data, $dataOffset)
                )
            );

            $self->values[$nameData] = $valueData;
            $keyValueLength = $dataOffset + $nameLength + $valueLength;
            $data = substr($data, $keyValueLength);

            $currentOffset += $keyValueLength;
        } while ($currentOffset < $self->getContentLength());
    }

    /**
     * @return string
     */
    protected function packPayload(): string
    {
        $payload = '';

        foreach ($this->values as $nameData => $valueData) {
            if ($valueData === null) {
                continue;
            }

            $nameLength = strlen($nameData);
            $valueLength = strlen((string) $valueData);
            $isLongName = $nameLength > 127;
            $isLongValue = $valueLength > 127;
            $formatParts = [
                $isLongName ? 'N' : 'C',
                $isLongValue ? 'N' : 'C',
                "a{$nameLength}",
                "a{$valueLength}"
            ];
            $format = join('', $formatParts);

            $payload .= pack(
                $format,
                $isLongName ? ($nameLength | 0x80000000) : $nameLength,
                $isLongValue ? ($valueLength | 0x80000000) : $valueLength,
                $nameData,
                $valueData
            );
        }

        return $payload;
    }
}
