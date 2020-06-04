<?php
declare(strict_types=1);

namespace Air\FCgi\Protocol;

use Air\FCgi\Protocol\Record\BeginRequestRecord;
use Air\FCgi\Protocol\Record\ParamsRecord;
use Air\FCgi\Protocol\Record\StdinRecord;
use Air\FCgi\MessageInterface;

/**
 * Class Request
 * @package Air\FCgi
 */
class Request
{
    /**
     * @var MessageInterface
     */
    protected $message;

    /**
     * @var int
     */
    protected $keepConn = Constant::KEEP_CONN;

    /**
     * @var int
     */
    protected $requestId = Constant::DEFAULT_REQUEST_ID;

    /**
     * Request constructor.
     * @param int|null $requestId
     * @param bool $keepConn
     * @param MessageInterface|null $message
     */
    public function __construct(int $requestId = null, bool $keepConn = false, MessageInterface $message = null)
    {
        $this->keepConn = intval($keepConn);
        $this->requestId = $requestId ?? Constant::DEFAULT_REQUEST_ID;

        if ($message) {
            $this->setMessage($message);
        }
    }

    /**
     * @param MessageInterface $message
     * @return $this
     */
    public function setMessage(MessageInterface $message): self
    {
        $this->message = $message;

        return $this;
    }

    /**
     * @return MessageInterface
     */
    public function getMessage(): MessageInterface
    {
        return $this->message;
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
        //begin
        $begin = new BeginRequestRecord(Constant::RESPONDER, $this->getKeepConn(), '');
        $begin->setRequestId($this->getRequestId());

        $message = (string)$begin;

        //if has message
        if ($this->message) {
            //params
            $params = new ParamsRecord($this->getMessage()->getParams());
            $params->setRequestId($this->getRequestId());

            $paramsEof = new ParamsRecord();
            $paramsEof->setRequestId($this->getRequestId());

            $message .= $params . $paramsEof;

            //stdin
            if (!empty($body = $this->message->getContent())) {
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

                $stdinList[] = $stdin = new StdinRecord();
                $stdin->setRequestId($this->getRequestId());

                $stdin = implode($stdinList);
                $message .= $stdin;
            }
        }

        return $message;
    }
}
