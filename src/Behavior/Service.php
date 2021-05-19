<?php


namespace Vox\Framework\Behavior;

/**
 * @Annotation
 * @Target({"CLASS"})
 */
class Service
{
    /**
     * @var string
     */
    public $beanName;
}