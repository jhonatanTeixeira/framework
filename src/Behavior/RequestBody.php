<?php


namespace Vox\Framework\Behavior;

/**
 * @Annotation
 * @Target({"METHOD"})
 */
class RequestBody
{
    /**
     * @var string
     */
    public $argName = null;

    /**
     * @var string
     */
    public $type = null;
}