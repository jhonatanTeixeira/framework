<?php


namespace Vox\Framework\Behavior;

/**
 * @Annotation
 * @Target({"CLASS"})
 */
class PreDispatch
{
    /**
     * @var int
     */
    public $priority;
}