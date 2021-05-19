<?php


namespace Vox\Framework\Behavior;

/**
 * @Annotation
 * @Target({"METHOD", "CLASS"})
 */
class UseMiddleware
{
    /**
     * @var string
     */
    public $middlewareClass;
}