<?php
declare(strict_types=1);

namespace Air\FCgi\Protocol\Record;

use Air\FCgi\Protocol\Constant;
use Air\FCgi\Protocol\Record;

/**
 * Class FastCGIRecordParams
 * @package Air\FCgi
 */
class StdoutRecord extends Record
{
    /**
     * StdoutRecord constructor.
     * @param string $contentData
     */
    public function __construct(string $contentData = '')
    {
        $this->type = Constant::STDOUT;

        $this->setContentData($contentData);
    }
}
