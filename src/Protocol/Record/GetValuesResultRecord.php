<?php
declare(strict_types=1);

namespace Air\FCgi\Protocol\Record;

use Air\FCgi\Protocol\Constant;

/**
 * Class GetValuesResultRecord
 * @package Air\FCgi\Record
 */
class GetValuesResultRecord extends ParamsRecord
{
    /**
     * GetValuesResultRecord constructor.
     * @param array $values
     */
    public function __construct(array $values = [])
    {
        parent::__construct($values);

        $this->type = Constant::GET_VALUES_RESULT;
    }
}
