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
    protected $keepConn = FastCGIConstant::KEEP_CONN;

    /**
     * @var int
     */
    protected $requestId = FastCGIConstant::DEFAULT_REQUEST_ID;

    /**
     * Request constructor.
     * @param StdinInterface $stdin
     * @param int|null $requestId
     * @param bool $keepConn
     */
    public function __construct(StdinInterface $stdin, int $requestId = null, bool $keepConn = false)
    {
        $this->stdin = $stdin;
        $this->keepConn = intval($keepConn);
        $this->requestId = $requestId ?? FastCGIConstant::DEFAULT_REQUEST_ID;
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
     * @return string
     */
    public function __toString(): string
    {
        //step1 begin
        $begin = new BeginRequestRecord(FastCGIConstant::RESPONDER, $this->getKeepConn(), '');
        $begin->setRequestId($this->getRequestId());

        //step2 params
        $params = new ParamsRecord($this->getStdin()->getParams());
        $params->setRequestId($this->getRequestId());

        $paramsEof = new ParamsRecord([]);
        $paramsEof->setRequestId($this->getRequestId());

        $message = $begin . $params . $paramsEof;

        //step3 stdin
        if (!empty($body = $this->getStdin()->getContent())) {
            $stdinList = [];
            $bodyLength = strlen($body);
            $stdinLength = 0;

            do {
                $stdinList[] = $stdin = new StdinRecord($body);
                $stdin->setRequestId($this->getRequestId());

                $stdinLength += $stdin->getContentLength();
                if ($stdinLength === $bodyLength) {
                    break;
                }

                $body = substr($body, $stdinLength);
            } while (true);

            $stdinList[] = $stdin = new StdinRecord('');
            $stdin->setRequestId($this->getRequestId());

            $stdin = implode($stdinList);
            $message .= $stdin;
        }

        return $message;
    }
}
