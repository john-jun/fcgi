<?php
declare(strict_types=1);

namespace Air\FCgi\Record;

use Air\FCgi\FastCGI;
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
        $this->type = FastCGI::ABORT_REQUEST;

        $this->setRequestId($requestId ?? FastCGI::DEFAULT_REQUEST_ID);
    }
}
