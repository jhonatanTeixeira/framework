<?php


namespace Vox\Framework\Behavior;

/**
 * @Annotation
 * @Target({"METHOD"})
 */
class Delete
{
    /**
     * @var string
     * @required
     */
    public $path;
}