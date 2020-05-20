<?php
declare(strict_types=1);

namespace Air\FCgi\Http\Content;

use Air\FCgi\ContentInterface;
use Exception;

/**
 * Class JsonContent
 * @package Air\FCgi\Http\Content
 */
class JsonContent implements ContentInterface
{
    /** @var array */
    private $data;

    /** @var int */
    private $encodingDepth;

    /** @var int */
    private $encodingOptions;

    /**
     * @param array $data
     * @param int   $options
     * @param int   $depth
     */
    public function __construct(array $data, int $options = 0, int $depth = 512)
    {
        $this->data = $data;
        $this->encodingDepth = $depth;
        $this->encodingOptions = $options;
    }

    /**
     * @return string
     * @throws Exception
     */
    public function getContent(): string
    {
        $json = json_encode($this->data, $this->encodingOptions, $this->encodingDepth);
        if (false === $json) {
            throw new Exception('Could not encode data to JSON');
        }

        return $json;
    }

    /**
     * @return string
     */
    public function getContentType(): string
    {
        return 'application/json';
    }
}