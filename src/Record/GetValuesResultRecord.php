<?php
declare(strict_types=1);

namespace Air\FCgi\Record;

use Air\FCgi\FastCGI;

/**
 * Class GetValuesResultRecord
 * @package Air\FCgi\Record
 */
class GetValuesResultRecord extends ParamsRecord
{
    /**
     * GetValuesResultRecord constructor.
     * @param array $values
     * @param int|null $requestId
     */
    public function __construct(array $values = [], int $requestId = null)
    {
        parent::__construct($values, $requestId);

        $this->type = FastCGI::GET_VALUES_RESULT;
    }
}
