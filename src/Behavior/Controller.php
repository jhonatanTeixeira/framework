<?php


namespace Vox\Framework\Behavior;

/**
 * @Annotation
 * @Target({"CLASS"})
 */
class Controller
{
    /**
     * @var string
     */
    public $path = '/';
}