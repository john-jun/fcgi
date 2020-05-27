<?php
declare(strict_types=1);

namespace Air\FCgi\Record;

use Air\FCgi\FastCGIConstant;

/**
 * Class FastCGIRecordParams
 * @package Air\FCgi
 */
class StdoutRecord extends AbstractRecord
{
    /**
     * StdoutRecord constructor.
     * @param string $contentData
     */
    public function __construct(string $contentData = '')
    {
        $this->type = FastCGIConstant::STDOUT;

        $this->setContentData($contentData);
    }
}
