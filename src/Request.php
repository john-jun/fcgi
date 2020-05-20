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
    protected $keepConn;

    /**
     * @var int
     */
    protected $requestId;

    /**
     * Request constructor.
     * @param StdinInterface $stdin
     * @param int|null $requestId
     */
    public function __construct(StdinInterface $stdin, int $requestId = null)
    {
        $this->stdin = $stdin;
        $this->requestId = $requestId ?? Constant::DEFAULT_REQUEST_ID;
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
        $begin = new BeginRequestRecord(Constant::RESPONDER, $this->getKeepConn(), '', $this->requestId);

        //step2 params
        $params = new ParamsRecord($this->getStdin()->getParams(), $this->requestId);
        $paramsEof = new ParamsRecord([], $this->requestId);
        $message = $begin . $params . $paramsEof;

        //step3 stdin
        if (!empty($body = $this->getStdin()->getContent())) {
            $stdinList = [];

            do {
                $stdinList[] = $stdin = new StdinRecord($body, $this->requestId);
                $stdinLength = $stdin->getContentLength();

                if ($stdinLength === strlen($body)) {
                    break;
                }

                $body = substr($body, $stdinLength);
            } while (true);

            $stdinList[] = new StdinRecord('', $this->requestId);
            $stdin = implode($stdinList);
            $message .= $stdin;
        }

        return $message;
    }
}
