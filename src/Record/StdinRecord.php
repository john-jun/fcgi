<?php
declare(strict_types=1);

namespace Air\FCgi\Record;

use Air\FCgi\FastCGIConstant;

/**
 * Class StdinRecord
 * @package Air\FCgi\Record
 */
class StdinRecord extends AbstractRecord
{
    /**
     * StdinRecord constructor.
     * @param string $contentData
     */
    public function __construct(string $contentData = '')
    {
        $this->type = FastCGIConstant::STDIN;

        $this->setContentData($contentData);
    }
}
