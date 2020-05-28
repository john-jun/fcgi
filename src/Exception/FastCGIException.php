<?php
declare(strict_types=1);
namespace Air\FCgi\Exception;

use Exception;

/**
 * Class FCgiException
 * @package Air\FCgi\Exception
 */
class FastCGIException extends Exception implements \Air\FCgi\FastCGIException
{
}
