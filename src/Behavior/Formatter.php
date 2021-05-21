<?php


namespace Vox\Framework\Behavior;

/**
 * @Annotation
 * @Target({"CLASS"})
 */
class Formatter
{
    /**
     * @var string
     */
    public $format = null;
}