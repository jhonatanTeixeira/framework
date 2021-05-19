<?php


namespace Vox\Framework\Behavior;

/**
 * @Annotation
 * @Target({"METHOD"})
 */
class Get
{
    /**
     * @var string
     * @required
     */
    public $path;
}