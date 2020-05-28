<?php
declare(strict_types=1);

namespace Air\FCgi\Http\Content;

use Air\FCgi\Http\ContentInterface;
use Exception;

/**
 * Class JsonContent
 * @package Air\FCgi\Http\Content
 */
class JsonContent implements ContentInterface
{
    /** @var array */
    private $data;

    /**
     * JsonContent constructor.
     * @param array $data
     * @param int $options
     * @param int $depth
     */
    public function __construct(array $data, int $options = 0, int $depth = 512)
    {
        $this->data = json_encode($data, $options, $depth);
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->data;
    }

    /**
     * @return string
     */
    public function getContentType(): string
    {
        return 'application/json';
    }
}