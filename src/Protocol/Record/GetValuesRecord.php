<?php
declare(strict_types=1);

namespace Air\FCgi\Protocol\Record;

use Air\FCgi\Protocol\Constant;

/**
 * Class GetValuesRecord
 * @package Air\FCgi\Record
 */
class GetValuesRecord extends ParamsRecord
{
    /**
     * GetValuesRecord constructor.
     * @param array $keys
     */
    public function __construct(array $keys = [])
    {
        parent::__construct(array_fill_keys($keys, ''));

        $this->type = Constant::GET_VALUES;
    }
}
