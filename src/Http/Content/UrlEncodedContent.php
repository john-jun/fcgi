<?php
declare(strict_types=1);

namespace Air\FCgi\Http\Content;

use Air\FCgi\ContentInterface;

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
        $this->data = $data;
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
        return http_build_query($this->data);
    }
}