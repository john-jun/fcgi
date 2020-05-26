<?php
declare(strict_types=1);

namespace Air\FCgi\Record;

use Air\FCgi\FastCGI;
use Air\FCgi\Record;

/**
 * Class UnknownTypeRecord
 * @package Air\FCgi\Record
 */
class UnknownTypeRecord extends Record
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
     * @param int|null $requestId
     */
    public function __construct(int $type = 0, string $reserved = '', int $requestId = null)
    {
        $this->type = FastCGI::UNKNOWN_TYPE;
        $this->type1 = $type;
        $this->reserved1 = $reserved;

        $this->setRequestId($requestId ?? FastCGI::DEFAULT_REQUEST_ID);
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
        return pack(
            'Ca7',
            $this->type1,
            $this->reserved1
        );
    }
}
