<?php
declare(strict_types=1);

namespace Air\FCgi\Record;

use Air\FCgi\Constant;
use Air\FCgi\Record;

/**
 * Class BeginRequestRecord
 * @package Air\FCgi\Record
 */
class BeginRequestRecord extends Record
{
    /**
     * @var int
     */
    protected $role = Constant::UNKNOWN_ROLE;

    /**
     * @var int
     */
    protected $flags;

    /**
     * @var string
     */
    protected $reserved1;

    /**
     * BeginRequestRecord constructor.
     * @param int $role
     * @param int $flags
     * @param string $reserved
     * @param int $requestId
     */
    public function __construct(int $role = Constant::UNKNOWN_ROLE, int $flags = 0, string $reserved = '', int $requestId = null)
    {
        $this->type = Constant::BEGIN_REQUEST;
        $this->role = $role;
        $this->flags = $flags;
        $this->reserved1 = $reserved;

        $this->setRequestId($requestId ?? Constant::DEFAULT_REQUEST_ID);
        $this->setContentData($this->packPayload());
    }

    /**
     * @param $self
     * @param string $data
     */
    protected static function unpackPayload($self, string $data): void
    {
       [
           $self->role,
           $self->flags,
           $self->reserved1
       ] = array_values(unpack('nrole/Cflags/a5reserved', $data));
    }

    /**
     * @return string
     */
    protected function packPayload(): string
    {
        return pack(
            'nCa5',
            $this->role,
            $this->flags,
            $this->reserved1
        );
    }
}
