<?php

namespace Vox\Framework\Behavior;

/**
 * @Annotation
 * @Target({"CLASS"})
 */
class ErrorHandler {
    
    /**
     * @var string
     */
    public $priotiry = 1;
}
