<?php


namespace Vox\Framework\Test\Behavior;

/**
 * @Annotation
 * @Target({"PROPERTY"})
 */
class Mock
{
    /**
     * @var string
     * @required
     */
    public $type;

    /**
     * @var string
     */
    public $serviceId;
}