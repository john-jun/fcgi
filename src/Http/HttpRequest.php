<?php
declare(strict_types=1);
namespace Air\FCgi\Http;

use Air\FCgi\Http\Content\UrlEncodedContent;
use Air\FCgi\Message\DefaultMessage;
use Air\FCgi\MessageInterface;
use Air\FCgi\Protocol\Request;

/**
 * Class HttpRequest
 * @package Air\FCgi\Http
 */
class HttpRequest extends Request
{
    /**
     * @var string[]
     */
    private $params = [
        'REQUEST_URI' => '/',
        'REQUEST_SCHEME' => 'http',
        'REQUEST_METHOD' => 'GET',

        'QUERY_STRING' => '',

        'DOCUMENT_URI' => '',
        'DOCUMENT_ROOT' => '',

        'SCRIPT_NAME' => '',
        'SCRIPT_FILENAME' => '',

        'CONTENT_TYPE' => '',
        'CONTENT_LENGTH' => 0,

        'GATEWAY_INTERFACE' => 'FastCGI/1.1',

        'SERVER_PROTOCOL' => 'HTTP/1.1',
        'SERVER_SOFTWARE' => 'PHP/' . PHP_VERSION,

        'SERVER_ADDR' => 'unknown',
        'SERVER_PORT' => '0',
        'SERVER_NAME' => 'PHPClient',

        'REMOTE_ADDR' => 'unknown',
        'REMOTE_PORT' => '0',

        'REDIRECT_STATUS' => '200'
    ];

    /**
     * @var ContentInterface
     */
    private $content = null;

    /**
     * HttpRequest constructor.
     * @param int|null $requestId
     * @param bool $keepConn
     */
    public function __construct(int $requestId = null, bool $keepConn = false)
    {
        parent::__construct($requestId, $keepConn, new DefaultMessage());
    }

    /**
     * @return array|string[]
     */
    public function getParams():array
    {
        return $this->params;
    }

    /**
     * @return MessageInterface
     */
    public function getMessage(): MessageInterface
    {
        if ($this->content instanceof ContentInterface) {
            switch ($this->params['REQUEST_METHOD']) {
                case 'GET':
                case 'HEAD':
                case 'DELETE':
                case 'OPTIONS':
                    if ($this->content instanceof UrlEncodedContent) {
                        $this
                            ->withQueryString($this->content->getContent())
                            ->withContentType($this->content->getContentType())
                            ->withContentLength(strlen($this->content->getContent()));

                        goto Message;
                    }
            }

            $this
                ->withContentType($this->content->getContentType())
                ->withContentLength(strlen($content = $this->content->getContent()));
        } else {
            $content = $this->content;
        }

        Message:
        parent::getMessage()
            ->setParams($this->getParams())
            ->setContent($content ?? '');

        return parent::getMessage();
    }

    /**
     * @param string $uri
     * @return $this
     */
    public function withUri(string $uri): self
    {
        $info = parse_url($uri);

        return $this->withRequestUri($uri)
            ->withDocumentUri($info['path'] ?? '')
            ->withQueryString($info['query'] ?? '');
    }

    /**
     * @param $content
     * @return $this
     */
    public function withContent($content): self
    {
        if ($content instanceof ContentInterface) {
            $this->content = $content;
        } else {
            $this->content = $content;
            $this->withContentLength(strlen($content));
        }

        return $this;
    }

    /**
     * @param string $scheme
     * @return $this
     */
    public function withScheme(string $scheme): self
    {
        $this->params['REQUEST_SCHEME'] = $scheme;

        return $this;
    }

    /**
     * @param string $method
     * @return $this
     */
    public function withMethod(string $method): self
    {
        $this->params['REQUEST_METHOD'] = strtoupper($method);

        return $this;
    }

    /**
     * @param string $documentRoot
     * @return $this
     */
    public function withDocumentRoot(string $documentRoot): self
    {
        $this->params['DOCUMENT_ROOT'] = $documentRoot;

        return $this;
    }

    /**
     * @param string $scriptFilename
     * @return $this
     */
    public function withScriptFilename(string $scriptFilename): self
    {
        $this->params['SCRIPT_FILENAME'] = $scriptFilename;
        $this->params['SCRIPT_NAME'] = pathinfo($scriptFilename, PATHINFO_BASENAME);

        $this->withDocumentRoot(pathinfo($scriptFilename, PATHINFO_DIRNAME));

        return $this;
    }

    /**
     * @param string $documentUri
     * @return $this
     */
    public function withDocumentUri(string $documentUri): self
    {
        $this->params['DOCUMENT_URI'] = $documentUri;

        return $this;
    }

    /**
     * @param string $requestUri
     * @return $this
     */
    public function withRequestUri(string $requestUri): self
    {
        $this->params['REQUEST_URI'] = $requestUri;

        return $this;
    }

    /**
     * @param $query
     * @return $this
     */
    public function withQueryString($query): self
    {
        if (is_array($query)) {
            $query = http_build_query($query);
        }

        $this->params['QUERY_STRING'] = $query;

        return $this;
    }

    /**
     * @param string $contentType
     * @return $this
     */
    public function withContentType(string $contentType): self
    {
        $this->params['CONTENT_TYPE'] = $contentType;

        return $this;
    }

    /**
     * @param int $contentLength
     * @return $this
     */
    public function withContentLength(int $contentLength): self
    {
        $this->params['CONTENT_LENGTH'] = (string) $contentLength;

        return $this;
    }

    /**
     * @param string $gatewayInterface
     * @return $this
     */
    public function withGatewayInterface(string $gatewayInterface): self
    {
        $this->params['GATEWAY_INTERFACE'] = $gatewayInterface;

        return $this;
    }

    /**
     * @param string $serverProtocol
     * @return $this
     */
    public function withServerProtocol(string $serverProtocol): self
    {
        $this->params['SERVER_PROTOCOL'] = $serverProtocol;

        return $this;
    }

    /**
     * @param string $serverSoftware
     * @return $this
     */
    public function withServerSoftware(string $serverSoftware): self
    {
        $this->params['SERVER_SOFTWARE'] = $serverSoftware;

        return $this;
    }

    /**
     * @param string $remoteAddr
     * @return $this
     */
    public function withRemoteAddr(string $remoteAddr): self
    {
        $this->params['REMOTE_ADDR'] = $remoteAddr;

        return $this;
    }

    /**
     * @param int $remotePort
     * @return $this
     */
    public function withRemotePort(int $remotePort): self
    {
        $this->params['REMOTE_PORT'] = (string) $remotePort;

        return $this;
    }

    /**
     * @param string $serverAddr
     * @return $this
     */
    public function withServerAddr(string $serverAddr): self
    {
        $this->params['SERVER_ADDR'] = $serverAddr;

        return $this;
    }

    /**
     * @param int $serverPort
     * @return $this
     */
    public function withServerPort(int $serverPort): self
    {
        $this->params['SERVER_PORT'] = (string) $serverPort;

        return $this;
    }

    /**
     * @param string $serverName
     * @return $this
     */
    public function withServerName(string $serverName): self
    {
        $this->params['SERVER_NAME'] = $serverName;

        return $this;
    }

    /**
     * @param string $redirectStatus
     * @return $this
     */
    public function withRedirectStatus(string $redirectStatus): self
    {
        $this->params['REDIRECT_STATUS'] = $redirectStatus;

        return $this;
    }

    /**
     * @param string $name
     * @param string $value
     * @return $this
     */
    public function withHeader(string $name, string $value): self
    {
        $this->params[static::convertHeaderNameToParamName($name)] = $value;

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
     * @param string $name
     * @return string
     */
    protected static function convertHeaderNameToParamName(string $name)
    {
        return 'HTTP_' . str_replace('-', '_', strtoupper($name));
    }

    /**
     * @param string $name
     * @return string
     */
    protected static function convertParamNameToHeaderName(string $name)
    {
        return ucwords(str_replace('_', '-', substr($name, strlen('HTTP_'))), '-');
    }
}
