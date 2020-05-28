<?php
declare(strict_types=1);

namespace Air\FCgi\Protocol\Record;

use Air\FCgi\Protocol\Constant;
use Air\FCgi\Protocol\Record;

/**
 * Class DataRecord
 * @package Air\FCgi\Record
 */
class DataRecord extends Record
{
    /**
     * DataRecord constructor.
     * @param string $contentData
     */
    public function __construct(string $contentData = '')
    {
        $this->type = Constant::DATA;

        $this->setContentData($contentData);
    }
}
