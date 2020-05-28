<?php
declare(strict_types=1);

namespace Air\FCgi\Http\Content;

use Air\FCgi\Http\ContentInterface;

/**
 * Class UrlEncodedContent
 * @package Air\FCgi\Http\Content
 */
class UrlEncodedContent implements ContentInterface
{
    /** @var array */
    private $data;

    /**
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->data = http_build_query($data);
    }

    /**
     * @return string
     */
    public function getContentType() : string
    {
        return 'application/x-www-form-urlencoded';
    }

    /**
     * @return string
     */
    public function getContent() : string
    {
        return $this->data;
    }
}