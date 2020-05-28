<?php
declare(strict_types=1);

namespace Air\FCgi\Protocol\Record;

use Air\FCgi\Protocol\Constant;
use Air\FCgi\Protocol\Record;

/**
 * Class AbortRequestRecord
 * @package Air\FCgi\Record
 */
class AbortRequestRecord extends Record
{
    /**
     * AbortRequestRecord constructor.
     */
    public function __construct()
    {
        $this->type = Constant::ABORT_REQUEST;
    }
}
