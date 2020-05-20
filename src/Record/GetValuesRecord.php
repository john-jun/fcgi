<?php
declare(strict_types=1);

namespace Air\FCgi\Record;

use Air\FCgi\Constant;

/**
 * Class GetValuesRecord
 * @package Air\FCgi\Record
 */
class GetValuesRecord extends ParamsRecord
{
    /**
     * GetValuesRecord constructor.
     * @param array $keys
     * @param int|null $requestId
     */
    public function __construct(array $keys = [], int $requestId = null)
    {
        parent::__construct(array_fill_keys($keys, ''), $requestId);

        $this->type = Constant::GET_VALUES;
    }
}
