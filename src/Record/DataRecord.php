<?php
declare(strict_types=1);

namespace Air\FCgi\Record;

use Air\FCgi\FastCGI;
use Air\FCgi\Record;

/**
 * Class DataRecord
 * @package Air\FCgi\Record
 */
class DataRecord extends Record
{
    /**
     * DataRecord constructor.
     * @param string $contentData
     * @param int|null $requestId
     */
    public function __construct(string $contentData = '', int $requestId = null)
    {
        $this->type = FastCGI::DATA;

        $this->setRequestId($requestId ?? FastCGI::DEFAULT_REQUEST_ID);
        $this->setContentData($contentData);
    }
}
