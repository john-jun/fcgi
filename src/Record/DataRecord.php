<?php
declare(strict_types=1);

namespace Air\FCgi\Record;

use Air\FCgi\FastCGIConstant;

/**
 * Class DataRecord
 * @package Air\FCgi\Record
 */
class DataRecord extends AbstractRecord
{
    /**
     * DataRecord constructor.
     * @param string $contentData
     */
    public function __construct(string $contentData = '')
    {
        $this->type = FastCGIConstant::DATA;

        $this->setContentData($contentData);
    }
}
