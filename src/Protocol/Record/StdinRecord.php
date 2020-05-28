<?php
declare(strict_types=1);

namespace Air\FCgi\Protocol\Record;

use Air\FCgi\Protocol\Constant;
use Air\FCgi\Protocol\Record;

/**
 * Class StdinRecord
 * @package Air\FCgi\Record
 */
class StdinRecord extends Record
{
    /**
     * StdinRecord constructor.
     * @param string $contentData
     */
    public function __construct(string $contentData = '')
    {
        $this->type = Constant::STDIN;

        $this->setContentData($contentData);
    }
}
