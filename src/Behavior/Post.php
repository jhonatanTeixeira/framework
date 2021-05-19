<?php


namespace Vox\Framework\Behavior;

/**
 * @Annotation
 * @Target({"METHOD"})
 */
class Post
{
    /**
     * @var string
     * @required
     */
    public $path;
}