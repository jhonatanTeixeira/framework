<?php


namespace Vox\Framework\Behavior;

/**
 * @Annotation
 * @Target({"METHOD"})
 */
class Put
{
    /**
     * @var string
     * @required
     */
    public $path;
}