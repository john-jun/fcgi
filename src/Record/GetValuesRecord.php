<?php
declare(strict_types=1);

namespace Air\FCgi\Record;

use Air\FCgi\FastCGIConstant;

/**
 * Class GetValuesRecord
 * @package Air\FCgi\Record
 */
class GetValuesRecord extends ParamsAbstractRecord
{
    /**
     * GetValuesRecord constructor.
     * @param array $keys
     */
    public function __construct(array $keys = [])
    {
        parent::__construct(array_fill_keys($keys, ''));

        $this->type = FastCGIConstant::GET_VALUES;
    }
}
