<?php
declare(strict_types=1);

namespace Air\FCgi\Protocol\Record;

use Air\FCgi\Protocol\Constant;
use Air\FCgi\Protocol\Record;

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
     */
    public function __construct(int $role = Constant::UNKNOWN_ROLE, int $flags = 0, string $reserved = '')
    {
        $this->type = Constant::BEGIN_REQUEST;
        $this->role = $role;
        $this->flags = $flags;
        $this->reserved1 = $reserved;

        $this->setContentData($this->packPayload());
    }

    /**
     * @param $self
     * @param string $data
     */
    protected static function unpackPayload($self, string $data): void
    {
       [$self->role, $self->flags, $self->reserved1] = array_values(unpack('nrole/Cflags/a5reserved', $data));
    }

    /**
     * @return string
     */
    protected function packPayload(): string
    {
        return pack('nCa5', $this->role, $this->flags, $this->reserved1);
    }
}
