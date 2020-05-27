<?php
declare(strict_types=1);

namespace Air\FCgi\Record;

use Air\FCgi\FastCGIConstant;

/**
 * Class StderrRecord
 * @package Air\FCgi\Record
 */
class StderrRecord extends AbstractRecord
{
    /**
     * StderrRecord constructor.
     * @param string $contentData
     */
    public function __construct(string $contentData = '')
    {
        $this->type = FastCGIConstant::STDERR;

        $this->setContentData($contentData);
    }
}
