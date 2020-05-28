<?php
declare(strict_types=1);

namespace Air\FCgi\Protocol\Record;

use Air\FCgi\Protocol\Constant;
use Air\FCgi\Protocol\Record;

/**
 * Class StderrRecord
 * @package Air\FCgi\Record
 */
class StderrRecord extends Record
{
    /**
     * StderrRecord constructor.
     * @param string $contentData
     */
    public function __construct(string $contentData = '')
    {
        $this->type = Constant::STDERR;

        $this->setContentData($contentData);
    }
}
