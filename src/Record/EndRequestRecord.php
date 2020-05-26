<?php
declare(strict_types=1);

namespace Air\FCgi\Record;

use Air\FCgi\FastCGI;
use Air\FCgi\Record;

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
    protected $protocolStatus = FastCGI::REQUEST_COMPLETE;

    /**
     * @var string
     */
    protected $reserved1;

    /**
     * EndRequestRecord constructor.
     * @param int $protocolStatus
     * @param int $appStatus
     * @param string $reserved
     * @param int|null $requestId
     */
    public function __construct(int $protocolStatus = FastCGI::REQUEST_COMPLETE, int $appStatus = 0, string $reserved = '', int $requestId = null) {
        $this->type = FastCGI::END_REQUEST;
        $this->appStatus = $appStatus;
        $this->reserved1 = $reserved;
        $this->protocolStatus = $protocolStatus;

        $this->setRequestId($requestId ?? FastCGI::DEFAULT_REQUEST_ID);
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
        [
            $self->appStatus,
            $self->protocolStatus,
            $self->reserved1
        ] = array_values(unpack('NappStatus/CprotocolStatus/a3reserved', $data));
    }

    /**
     * @return string
     */
    protected function packPayload(): string
    {
        return pack(
            'NCa3',
            $this->appStatus,
            $this->protocolStatus,
            $this->reserved1
        );
    }
}
