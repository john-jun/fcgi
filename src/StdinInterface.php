<?php
declare(strict_types=1);

namespace Air\FCgi;

/**
 * Interface BodyInterface
 * @package Air\FCgi
 */
interface StdinInterface
{
    public function getParams() : array;
    public function getContent() : string;
}
