<?php
declare(strict_types=1);

namespace Air\FCgi\Http;

/**
 * Interface ContentInterface
 * @package Air\FCgi
 */
interface ContentInterface
{
    public function getContent() : string;
    public function getContentType() : string;
}
