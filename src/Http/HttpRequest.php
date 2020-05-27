<?php
declare(strict_types=1);
namespace Air\FCgi\Http;

use Air\FCgi\Request;
use InvalidArgumentException;

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
        'REQUEST_URI' => '',
        'REQUEST_SCHEME' => 'http',
        'REQUEST_METHOD' => 'GET',

        'QUERY_STRING' => '',

        'DOCUMENT_URI' => '',
        'DOCUMENT_ROOT' => '',

        'SCRIPT_NAME' => '',
        'SCRIPT_FILENAME' => '',

        'CONTENT_TYPE' => '',
        'CONTENT_LENGTH' => '',
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
     * @return string|null
     */
    public function getScheme(): ?string
    {
        return $this->params['REQUEST_SCHEME'] ?? null;
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
     * @return string|null
     */
    public function getMethod(): ?string
    {
        return $this->params['REQUEST_METHOD'] ?? null;
    }

    /**
     * @param string $method
     * @return $this
     */
    public function withMethod(string $method): self
    {
        $this->params['REQUEST_METHOD'] = $method;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getDocumentRoot(): ?string
    {
        return $this->params['DOCUMENT_ROOT'] ?? null;
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
     * @return string|null
     */
    public function getScriptFilename(): ?string
    {
        return $this->params['SCRIPT_FILENAME'] ?? null;
    }

    /**
     * @param string $scriptFilename
     * @return $this
     */
    public function withScriptFilename(string $scriptFilename): self
    {
        $this->params['SCRIPT_FILENAME'] = $scriptFilename;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getScriptName(): ?string
    {
        return $this->params['SCRIPT_NAME'] ?? null;
    }

    /**
     * @param string $scriptName
     * @return $this
     */
    public function withScriptName(string $scriptName): self
    {
        $this->params['SCRIPT_NAME'] = $scriptName;

        return $this;
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
     * @return string|null
     */
    public function getDocumentUri(): ?string
    {
        return $this->params['DOCUMENT_URI'] ?? null;
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
     * @return string|null
     */
    public function getRequestUri(): ?string
    {
        return $this->params['REQUEST_URI'] ?? null;
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
     * @return string|null
     */
    public function getQueryString(): ?string
    {
        return $this->params['QUERY_STRING'] ?? null;
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
     * @return string|null
     */
    public function getContentType(): ?string
    {
        return $this->params['CONTENT_TYPE'] ?? null;
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
     * @return int|null
     */
    public function getContentLength(): ?int
    {
        return isset($this->params['CONTENT_LENGTH']) ? (int) $this->params['CONTENT_LENGTH'] : null;
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
     * @return string|null
     */
    public function getGatewayInterface(): ?string
    {
        return $this->params['GATEWAY_INTERFACE'] ?? null;
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
     * @return string|null
     */
    public function getServerProtocol(): ?string
    {
        return $this->params['SERVER_PROTOCOL'] ?? null;
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
     * @param string $protocolVersion
     * @return $this
     */
    public function withProtocolVersion(string $protocolVersion): self
    {
        if (!is_numeric($protocolVersion)) {
            throw new InvalidArgumentException('Protocol version must be numeric');
        }

        $this->params['SERVER_PROTOCOL'] = "HTTP/{$protocolVersion}";

        return $this;
    }

    /**
     * @return string|null
     */
    public function getServerSoftware(): ?string
    {
        return $this->params['SERVER_SOFTWARE'] ?? null;
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
     * @return string|null
     */
    public function getRemoteAddr(): ?string
    {
        return $this->params['REMOTE_ADDR'] ?? null;
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
     * @return int|null
     */
    public function getRemotePort(): ?int
    {
        return isset($this->params['REMOTE_PORT']) ? (int) $this->params['REMOTE_PORT'] : null;
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
     * @return string|null
     */
    public function getServerAddr(): ?string
    {
        return $this->params['SERVER_ADDR'] ?? null;
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
     * @return int|null
     */
    public function getServerPort(): ?int
    {
        return isset($this->params['SERVER_PORT']) ? (int) $this->params['SERVER_PORT'] : null;
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
     * @return string|null
     */
    public function getServerName(): ?string
    {
        return $this->params['SERVER_NAME'] ?? null;
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
     * @return string|null
     */
    public function getRedirectStatus(): ?string
    {
        return $this->params['REDIRECT_STATUS'] ?? null;
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
     * @return string|null
     */
    public function getHeader(string $name): ?string
    {
        return $this->params[static::convertHeaderNameToParamName($name)] ?? null;
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
     * @return array
     */
    public function getHeaders(): array
    {
        $headers = [];
        foreach ($this->params as $name => $value) {
            if (strpos($name, 'HTTP_') === 0) {
                $headers[static::convertParamNameToHeaderName($name)] = $value;
            }
        }

        return $headers;
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
