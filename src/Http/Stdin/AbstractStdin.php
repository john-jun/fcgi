<?php
declare(strict_types=1);

namespace Air\FCgi\Http\Stdin;

use Air\FCgi\ContentInterface;
use Air\FCgi\Http\Content\UrlEncodedContent;
use Air\FCgi\StdinInterface;

/**
 * Class AbstractStdin
 * @package Air\FCgi\Stdin
 */
class AbstractStdin implements StdinInterface
{
    /** @var string */
    private $scriptFilename;

    /** @var string */
    private $serverSoftware = 'PHP/' . PHP_VERSION;

    /** @var string */
    private $content = '';

    /** @var string */
    private $contentType = '';

    /** @var int */
    private $contentLength = 0;

    /** @var string */
    private $requestUri = '';

    /** @var array */
    private $customVars = [];

    /** @var string */
    private $queryString = '';

    /** @var string */
    private $requestMethod = null;

    /**
     * AbstractStdin constructor.
     * @param ContentInterface $content
     */
    public function __construct(ContentInterface $content)
    {
        $this->setContent($content);
    }

    /**
     * @return string
     */
    public function getRequestMethod() : string
    {
        return $this->requestMethod ?? static::METHOD;
    }

    /**
     * @param string $method
     * @return $this
     */
    public function setRequestMethod(string $method) : self
    {
        $this->requestMethod = $method;

        return $this;
    }

    /**
     * @return string
     */
    public function getQueryString() : string
    {
        return $this->queryString;
    }

    /**
     * @param string $querystring
     * @return $this
     */
    public function setQueryString(string $querystring) : self
    {
        $this->queryString = $querystring;

        return $this;
    }

    /**
     * @return string
     */
    public function getServerSoftware() : string
    {
        return $this->serverSoftware;
    }

    /**
     * @param string $serverSoftware
     * @return $this
     */
    public function setServerSoftware(string $serverSoftware) : self
    {
        $this->serverSoftware = $serverSoftware;

        return $this;
    }

    /**
     * @return string
     */
    public function getContentType() : string
    {
        return $this->contentType;
    }

    /**
     * @param string $contentType
     * @return $this
     */
    public function setContentType(string $contentType) : self
    {
        $this->contentType = $contentType;

        return $this;
    }

    /**
     * @param $content
     * @return $this
     */
    public function setContent($content) : self
    {
        if ($content instanceof ContentInterface) {
            if ($content instanceof UrlEncodedContent) {
                if (in_array(static::METHOD, ['GET', 'HEAD', 'DELETE', 'OPTIONS'])) {
                    return $this->setQueryString($content->getContent());
                }
            }

            $this->content = $content->getContent();
            $this->contentLength = strlen($this->content);
            $this->setContentType($content->getContentType());
        } else {
            $this->content = $content;
            $this->contentLength = strlen($content);
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getContent() : string
    {
        return $this->content;
    }

    /**
     * @return int
     */
    public function getContentLength() : int
    {
        return $this->contentLength;
    }

    /**
     * @return string
     */
    public function getRequestUri() : string
    {
        return $this->requestUri;
    }

    /**
     * @param string $requestUri
     * @return $this
     */
    public function setRequestUri(string $requestUri) : self
    {
        $this->requestUri = $requestUri;

        return $this;
    }

    /**
     * @param array $vars
     * @return $this
     */
    public function setCustomVars(array $vars) : self
    {
        $this->customVars = array_merge($this->customVars, $vars);

        return $this;
    }

    /**
     * @return array
     */
    public function getCustomVars() : array
    {
        return $this->customVars;
    }

    /**
     * @param string $filename
     * @return $this
     */
    public function setScriptFilename(string $filename) : self
    {
        $this->scriptFilename = $filename;

        return $this;
    }

    /**
     * @return string
     */
    public function getScriptFilename() : string
    {
        return $this->scriptFilename;
    }

    /**
     * @return array
     */
    public function getParams() : array
    {
        return array_merge(
            [
                'REQUEST_URI' => $this->getRequestUri(),
                'REQUEST_METHOD' => $this->getRequestMethod(),
                'SCRIPT_FILENAME' => $this->getScriptFilename(),
                'SERVER_SOFTWARE' => $this->getServerSoftware(),
                'QUERY_STRING' => $this->getQueryString(),
                'CONTENT_TYPE' => $this->getContentType(),
                'CONTENT_LENGTH' => $this->getContentLength()
            ],
            $this->getCustomVars(),
            [
                'SERVER_PROTOCOL' => 'HTTP/1.1',
                'GATEWAY_INTERFACE' => 'FastCGI/1.1'
            ]
        );
    }
}
