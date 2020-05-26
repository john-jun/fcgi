<?php
declare(strict_types=1);

namespace Air\FCgi;

use Air\FCgi\Record\BeginRequestRecord;
use Air\FCgi\Record\ParamsRecord;
use Air\FCgi\Record\StdinRecord;

/**
 * Class Request
 * @package Air\FCgi
 */
class Request
{
    /**
     * @var StdinInterface
     */
    protected $stdin;

    /**
     * @var int
     */
    protected $keepConn = FastCGI::KEEP_CONN;

    /**
     * @var int
     */
    protected $requestId = FastCGI::DEFAULT_REQUEST_ID;

    /**
     * Request constructor.
     * @param StdinInterface $stdin
     * @param int|null $requestId
     */
    public function __construct(StdinInterface $stdin, int $requestId = null)
    {
        $this->stdin = $stdin;
        $this->requestId = $requestId ?? FastCGI::DEFAULT_REQUEST_ID;
    }

    /**
     * @return StdinInterface
     */
    public function getStdin() : StdinInterface
    {
        return $this->stdin;
    }

    /**
     * @return int
     */
    public function getRequestId(): int
    {
        return $this->requestId;
    }

    /**
     * @return int
     */
    public function getKeepConn() : int
    {
        return $this->keepConn;
    }

    /**
     * @param bool $keepConn
     * @return $this
     */
    public function setKeepConn(bool $keepConn) : self
    {
        $this->keepConn = intval($keepConn);

        return $this;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        //step1 begin
        $begin = new BeginRequestRecord(FastCGI::RESPONDER, $this->getKeepConn(), '', $this->getRequestId());

        //step2 params
        $params = new ParamsRecord($this->getStdin()->getParams(), $this->getRequestId());
        $paramsEof = new ParamsRecord([], $this->getRequestId());
        $message = $begin . $params . $paramsEof;

        //step3 stdin
        if (!empty($body = $this->getStdin()->getContent())) {
            $stdinList = [];
            $bodyLength = strlen($body);
            $stdinLength = 0;

            do {
                $stdinList[] = $stdin = new StdinRecord($body, $this->getRequestId());
                $stdinLength += $stdin->getContentLength();

                if ($stdinLength === $bodyLength) {
                    break;
                }

                $body = substr($body, $stdinLength);
            } while (true);

            $stdinList[] = new StdinRecord('', $this->getRequestId());
            $stdin = implode($stdinList);
            $message .= $stdin;
        }

        return $message;
    }
}
