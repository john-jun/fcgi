<?php
declare(strict_types=1);

namespace Air\FCgi\Http\Stdin;

/**
 * Class HeadStdin
 * @package Air\FCgi\Http\Stdin
 */
class HeadStdin extends AbstractStdin
{
    protected const METHOD = 'HEAD';
}