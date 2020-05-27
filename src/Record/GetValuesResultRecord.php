<?php
declare(strict_types=1);

namespace Air\FCgi\Record;

use Air\FCgi\FastCGIConstant;

/**
 * Class GetValuesResultRecord
 * @package Air\FCgi\Record
 */
class GetValuesResultRecord extends ParamsAbstractRecord
{
    /**
     * GetValuesResultRecord constructor.
     * @param array $values
     */
    public function __construct(array $values = [])
    {
        parent::__construct($values);

        $this->type = FastCGIConstant::GET_VALUES_RESULT;
    }
}
