<?php
declare(strict_types=1);

namespace Air\FCgi;

use Air\FCgi\Record\EndRequestRecord;
use Air\FCgi\Record\StderrRecord;
use Air\FCgi\Record\StdoutRecord;
use InvalidArgumentException;

/**
 * Class Response
 * @package Air\FCgi
 */
class Response
{
    /**
     * @var string
     */
    protected $body = '';

    /**
     * @var string
     */
    protected $error = '';

    /**
     * @var int
     */
    protected $requestId;

    /**
     * Response constructor.
     * @param array $records
     */
    public function __construct(array $records = [])
    {
        if (!static::verify($records)) {
            throw new InvalidArgumentException('FastCGI bad records');
        }

        $body = '';
        $error = '';

        foreach ($records as $record) {
            if ($record instanceof StdoutRecord) {
                if ($record->getContentLength() > 0) {
                    $body .= $record->getContentData();
                }
            } elseif ($record instanceof StderrRecord) {
                if ($record->getContentLength() > 0) {
                    $error .= $record->getContentData();
                }
            }
        }

        $this->setBody($body)->setError($error);
    }

    /**
     * @param array $records
     * @return bool
     */
    public static function verify(array $records): bool
    {
        return !empty($records) && $records[count($records) - 1] instanceof EndRequestRecord;
    }

    /**
     * @return string
     */
    public function getBody(): string
    {
        return $this->body;
    }

    /**
     * @return string
     */
    public function getError(): string
    {
        return $this->error;
    }

    public function getRequestId(): int
    {
        return $this->requestId;
    }

    /**
     * @param $body
     * @return $this
     */
    public function setBody($body): self
    {
        $this->body = $body;

        return $this;
    }

    /**
     * @param string $error
     * @return $this
     */
    public function setError(string $error): self
    {
        $this->error = $error;

        return $this;
    }

    public function setRequestId(int $requestId)
    {
        $this->requestId = $requestId;

        return $this;
    }
}
