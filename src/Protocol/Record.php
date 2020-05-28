<?php
declare(strict_types=1);

namespace Air\FCgi\Protocol;

/**
 * Class Record
 * @package Air\FCgi
 */
class Record
{
    /**
     * @var int
     */
    protected $type = Constant::UNKNOWN_TYPE;

    /**
     * @var int
     */
    protected $version = Constant::VERSION_1;

    /**
     * @var int
     */
    protected $requestId = Constant::DEFAULT_REQUEST_ID;

    /**
     * @var int
     */
    protected $reserved = 0;

    /**
     * @var int
     */
    private $contentLength = 0;

    /**
     * @var int
     */
    private $paddingLength = 0;

    /**
     * @var string
     */
    private $contentData = '';

    /**
     * @var string
     */
    private $paddingData = '';

    /**
     * @return string
     */
    final public function __toString(): string
    {
        $headerPacket = pack(
            'CCnnCC',
            $this->version,
            $this->type,
            $this->requestId,
            $this->contentLength,
            $this->paddingLength,
            $this->reserved
        );

        $payloadPacket = $this->packPayload();
        $paddingPacket = pack("a{$this->paddingLength}", $this->paddingData);

        return $headerPacket . $payloadPacket . $paddingPacket;
    }

    /**
     * @param string $data
     * @param array $header
     * @return static
     */
    final public static function unpack(string $data, array $header = []): self
    {
        $self = new static();

        if ($header) {
            $self->type = $header['type'];
            $self->version = $header['version'];
            $self->reserved = $header['reserved'];
            $self->requestId = $header['requestId'];
            $self->contentLength = $header['contentLength'];
            $self->paddingLength = $header['paddingLength'];
        } else {
            [
                $self->version,
                $self->type,
                $self->requestId,
                $self->contentLength,
                $self->paddingLength,
                $self->reserved
            ] = array_values(unpack(Constant::HEADER_FORMAT, $data));
        }

        $payload = substr($data, Constant::HEADER_LEN);
        self::unpackPayload($self, $payload);

        if (get_called_class() !== __CLASS__ && $self->contentLength > 0) {
            static::unpackPayload($self, $payload);
        }

        return $self;
    }

    /**
     * @param string $data
     * @return $this
     */
    public function setContentData(string $data): self
    {
        $this->contentLength = strlen($data);

        if ($this->contentLength > Constant::MAX_CONTENT_LENGTH) {
            $this->contentLength = Constant::MAX_CONTENT_LENGTH;
            $this->contentData = substr($data, 0, Constant::MAX_CONTENT_LENGTH);
        } else {
            $this->contentData = $data;
        }

        $extraLength = $this->contentLength % 8;
        $this->paddingLength = $extraLength ? (8 - $extraLength) : 0;

        return $this;
    }

    /**
     * @return string
     */
    public function getContentData(): string
    {
        return $this->contentData;
    }

    /**
     * @return int
     */
    public function getVersion(): int
    {
        return $this->version;
    }

    /**
     * @return int
     */
    public function getType(): int
    {
        return $this->type;
    }

    /**
     * @return int
     */
    public function getRequestId(): int
    {
        return $this->requestId;
    }

    /**
     * @param int $requestId
     * @return $this
     */
    public function setRequestId(int $requestId): self
    {
        $this->requestId = $requestId;

        return $this;
    }

    /**
     * @return int
     */
    final public function getContentLength(): int
    {
        return $this->contentLength;
    }

    /**
     * @return int
     */
    final public function getPaddingLength(): int
    {
        return $this->paddingLength;
    }

    /**
     * @param $self
     * @param string $data
     */
    protected static function unpackPayload($self, string $data): void
    {
        [$self->contentData, $self->paddingData] = array_values(
            unpack("a{$self->contentLength}contentData/a{$self->paddingLength}paddingData", $data)
        );
    }

    /**
     * @return string
     */
    protected function packPayload(): string
    {
        return pack("a{$this->contentLength}", $this->contentData);
    }
}
