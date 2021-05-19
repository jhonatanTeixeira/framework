<?php


namespace Vox\Framework\Behavior;

/**
 * @Annotation
 * @Target({"CLASS"})
 */
class Interceptor
{
    /**
     * @var int
     */
    public $priority;
}