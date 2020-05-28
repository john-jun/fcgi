<?php
declare(strict_types=1);
namespace Air\FCgi\Message;

use Air\FCgi\MessageInterface;

/**
 * Class StdinDefault
 * @package Air\FCgi\Stdin
 */
class DefaultMessage implements MessageInterface
{
    /**
     * @var array
     */
    protected $params = [];

    /**
     * @var string
     */
    protected $content = '';

    /**
     * @return array
     */
    public function getParams(): array
    {
        return array_merge([
            'REQUEST_URI' => '/',
            'REQUEST_METHOD' => 'GET',
            'SERVER_PROTOCOL' => 'HTTP/1.1',
            'SCRIPT_FILENAME' => $this->getScriptFilename(),
            'GATEWAY_INTERFACE' => 'FastCGI/1.1'
        ], $this->params);
    }

    /**
     * @param array $params
     * @return $this
     */
    public function setParams(array $params): self
    {
        $this->params = $params;

        return $this;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @param string $content
     * @return $this
     */
    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    /**
     * @return string
     */
    public function getScriptFilename(): string
    {
        return $this->params['SCRIPT_FILENAME'] ?? '';
    }

    /**
     * @param string $filename
     * @return $this
     */
    public function withScriptFilename(string $filename): self
    {
        $this->params['SCRIPT_FILENAME'] = $filename;

        return $this;
    }
}
