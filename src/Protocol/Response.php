<?php
declare(strict_types=1);

namespace Air\FCgi\Protocol;

use Air\FCgi\Exception\FastCGIException;
use Air\FCgi\Protocol\Record\EndRequestRecord;
use Air\FCgi\Protocol\Record\StderrRecord;
use Air\FCgi\Protocol\Record\StdoutRecord;
use Air\FCgi\Protocol\Record\UnknownTypeRecord;

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
     * @throws FastCGIException
     */
    public function __construct(array $records = [])
    {
        if (!$this->verify($records)) {
            throw new FastCGIException('FastCGI bad records');
        }

        foreach ($records as $record) {
            if ($record instanceof StdoutRecord) {
                if ($record->getContentLength() > 0) {
                    $this->body .= $record->getContentData();
                }
            } elseif ($record instanceof StderrRecord) {
                if ($record->getContentLength() > 0) {
                    $this->error .= $record->getContentData();
                }
            } elseif ($record instanceof UnknownTypeRecord) {
                throw new FastCGIException('unexpected type ' . $record->getUnrecognizedType());
            }
        }
    }

    /**
     * @param array $records
     * @return bool
     * @throws FastCGIException
     */
    public function verify(array $records): bool
    {
        $lastRecord = $records[count($records) - 1] ?? null;
        if (empty($records) || !$lastRecord instanceof EndRequestRecord) {
            return false;
        }

        switch ($lastRecord->getProtocolStatus()) {
            case Constant::REQUEST_COMPLETE:
                $this->requestId = $lastRecord->getRequestId();
                return true;

            case Constant::CANT_MPX_CONN:
                throw new FastCGIException('This app can\'t multiplex [CANT_MPX_CONN]');

            case Constant::OVERLOADED:
                throw new FastCGIException('New request rejected; too busy [OVERLOADED]');

            case Constant::UNKNOWN_ROLE:
                throw new FastCGIException('Role value not known [UNKNOWN_ROLE]');

            default:
                throw new FastCGIException('Unknown content.');
        }
    }

    /**
     * @return string
     */
    public function getBody(): string
    {
        return $this->body;
    }

    /**
     * @param string $body
     * @return $this
     */
    public function setBody(string $body): self
    {
        $this->body = $body;

        return $this;
    }

    /**
     * @return string
     */
    public function getError(): string
    {
        return $this->error;
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

    /**
     * @return int
     */
    public function getRequestId(): int
    {
        return $this->requestId;
    }
}
