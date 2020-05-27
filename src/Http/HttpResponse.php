<?php
declare(strict_types=1);
namespace Air\FCgi\Http;

use Air\FCgi\Exception\FastCGIException;
use Air\FCgi\Response;
use Air\Http\HttpStatus;

/**
 * Class HttpResponse
 * @package Air\FCgi\Http
 */
class HttpResponse extends Response
{
    /**
     * @var int
     */
    protected $statusCode;

    /**
     * @var string
     */
    protected $reasonPhrase;

    /**
     * @var array
     */
    protected $headers = [];

    /**
     * @var array
     */
    protected $headersMap = [];

    /**
     * @var array
     */
    protected $setCookieHeaderLines = [];

    /**
     * HttpResponse constructor.
     * @param array $records
     * @throws FastCGIException
     */
    public function __construct(array $records = [])
    {
        parent::__construct($records);

        $this->parseHttpMessage($this->getBody());
    }

    /**
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * @param int $statusCode
     * @return $this
     */
    public function withStatusCode(int $statusCode): self
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    /**
     * @return string
     */
    public function getReasonPhrase(): string
    {
        return $this->reasonPhrase;
    }

    /**
     * @param string $reasonPhrase
     * @return $this
     */
    public function withReasonPhrase(string $reasonPhrase): self
    {
        $this->reasonPhrase = $reasonPhrase;

        return $this;
    }

    /**
     * @param string $name
     * @return string|null
     */
    public function getHeader(string $name): ?string
    {
        $name = $this->headersMap[strtolower($name)] ?? null;

        return $name ? $this->headers[$name] : null;
    }

    /**
     * @return array
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * @param string $name
     * @param string $value
     * @return $this
     */
    public function withHeader(string $name, string $value): self
    {
        $this->headers[$name] = $value;
        $this->headersMap[strtolower($name)] = $name;

        return $this;
    }

    /**
     * @param array $headers
     * @return $this
     */
    public function withHeaders(array $headers): self
    {
        foreach ($headers as $name => $value) {
            $this->withHeader($name, $value);
        }

        return $this;
    }

    /**
     * @return array
     */
    public function getSetCookieHeaderLines(): array
    {
        return $this->setCookieHeaderLines;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function withSetCookieHeaderLine(string $value): self
    {
        $this->setCookieHeaderLines[] = $value;

        return $this;
    }

    /**
     * @param string $httpMessage
     */
    protected function parseHttpMessage(string $httpMessage)
    {
        if (strlen($httpMessage) === 0) {
            return;
        }

        //An array that contains the HTTP headers and the body.
        $headerAndBody = explode("\r\n\r\n", $httpMessage, 2);
        if (count($headerAndBody) != 2) {
            $this->withStatusCode(HttpStatus::BAD_GATEWAY)
                ->withReasonPhrase('Invalid FastCGI Response')
                ->setBody($httpMessage);

            return;
        }

        $headers = explode("\r\n", $headerAndBody[0]);
        $body = $headerAndBody[1];

        foreach ($headers as $header) {
            //An array that contains the name and the value of an HTTP header.
            $array = explode(':', $header, 2);

            //Invalid HTTP header? Ignore it!
            if (count($array) != 2) {
                continue;
            }

            $name = trim($array[0]);
            $value = trim($array[1]);

            if (strcasecmp($name, 'Status') === 0) {
                //An array that contains the status code (and the reason phrase).
                $array = explode(' ', $value, 2);
                $statusCode = $array[0];
                $reasonPhrase = $array[1] ?? null;
            } elseif (strcasecmp($name, 'Set-Cookie') === 0) {
                $this->withSetCookieHeaderLine($value);
            } else {
                $this->withHeader($name, $value);
            }
        }

        $statusCode = (int)($statusCode ?? HttpStatus::OK);
        $reasonPhrase = (string)($reasonPhrase ?? HttpStatus::getReasonPhrase($statusCode));

        $this->withStatusCode($statusCode)
            ->withReasonPhrase($reasonPhrase)
            ->setBody($body);
    }
}
