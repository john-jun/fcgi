<?php
declare(strict_types=1);

namespace Air\FCgi\Protocol\Record;

use Air\FCgi\Protocol\Constant;
use Air\FCgi\Protocol\Record;

/**
 * Class EndRequestRecord
 * @package Air\FCgi\Record
 */
class EndRequestRecord extends Record
{
    /**
     * @var int
     */
    protected $appStatus = 0;

    /**
     * @var int
     */
    protected $protocolStatus = Constant::REQUEST_COMPLETE;

    /**
     * @var string
     */
    protected $reserved1;

    /**
     * EndRequestRecord constructor.
     * @param int $protocolStatus
     * @param int $appStatus
     * @param string $reserved
     */
    public function __construct(int $protocolStatus = Constant::REQUEST_COMPLETE, int $appStatus = 0, string $reserved = '') {
        $this->type = Constant::END_REQUEST;
        $this->appStatus = $appStatus;
        $this->reserved1 = $reserved;
        $this->protocolStatus = $protocolStatus;

        $this->setContentData($this->packPayload());
    }

    /**
     * @return int
     */
    public function getAppStatus(): int
    {
        return $this->appStatus;
    }

    /**
     * @return int
     */
    public function getProtocolStatus(): int
    {
        return $this->protocolStatus;
    }

    /**
     * @param $self
     * @param string $data
     */
    protected static function unpackPayload($self, string $data): void
    {
        [$self->appStatus, $self->protocolStatus, $self->reserved1] = array_values(unpack('NappStatus/CprotocolStatus/a3reserved', $data));
    }

    /**
     * @return string
     */
    protected function packPayload(): string
    {
        return pack('NCa3', $this->appStatus, $this->protocolStatus, $this->reserved1);
    }
}
