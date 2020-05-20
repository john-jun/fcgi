<?php
declare(strict_types=1);

namespace Air\FCgi\Record;

use Air\FCgi\Constant;
use Air\FCgi\Record;

/**
 * Class AbortRequestRecord
 * @package Air\FCgi\Record
 */
class AbortRequestRecord extends Record
{
    /**
     * AbortRequestRecord constructor.
     * @param int $requestId
     */
    public function __construct(int $requestId = null)
    {
        $this->type = Constant::ABORT_REQUEST;

        $this->setRequestId($requestId ?? Constant::DEFAULT_REQUEST_ID);
    }
}
