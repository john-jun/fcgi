<?php
declare(strict_types=1);

namespace Air\FCgi\Record;

use Air\FCgi\FastCGIConstant;

/**
 * Class AbortRequestRecord
 * @package Air\FCgi\Record
 */
class AbortRequestRecord extends AbstractRecord
{
    /**
     * AbortRequestRecord constructor.
     */
    public function __construct()
    {
        $this->type = FastCGIConstant::ABORT_REQUEST;
    }
}
