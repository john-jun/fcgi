<?php
declare(strict_types=1);

namespace Air\FCgi\Record;

use Air\FCgi\FastCGIConstant;

/**
 * Class UnknownTypeRecord
 * @package Air\FCgi\Record
 */
class UnknownTypeRecord extends AbstractRecord
{
    /**
     * @var int
     */
    protected $type1;

    /**
     * @var string
     */
    protected $reserved1;

    /**
     * UnknownTypeRecord constructor.
     * @param int $type
     * @param string $reserved
     */
    public function __construct(int $type = 0, string $reserved = '')
    {
        $this->type = FastCGIConstant::UNKNOWN_TYPE;
        $this->type1 = $type;
        $this->reserved1 = $reserved;

        $this->setContentData($this->packPayload());
    }

    /**
     * @return int
     */
    public function getUnrecognizedType(): int
    {
        return $this->type1;
    }

    /**
     * @param $self
     * @param string $data
     */
    public static function unpackPayload($self, string $data): void
    {
        [$self->type1, $self->reserved1] = array_values(unpack('Ctype/a7reserved', $data));
    }

    /**
     * @return string
     */
    protected function packPayload(): string
    {
        return pack('Ca7', $this->type1, $this->reserved1);
    }
}
