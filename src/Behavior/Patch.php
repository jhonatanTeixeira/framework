<?php


namespace Vox\Framework\Behavior;

/**
 * @Annotation
 * @Target({"METHOD"})
 */
class Patch
{
    /**
     * @var string
     * @required
     */
    public $path;
}