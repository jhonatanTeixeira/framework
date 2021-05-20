<?php


namespace Vox\Framework\Tests\Controller;

use Vox\Framework\Behavior\Controller;
use Vox\Framework\Behavior\Get;

/**
 * @Controller("/foo")
 */
class FooController
{
    /**
     * @Get()
     */
    public function get() {
        return ["asdasd" => "asdasdasd"];
    }
}