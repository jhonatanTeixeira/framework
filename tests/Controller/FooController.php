<?php


namespace Vox\Framework\Tests\Controller;

use Vox\Framework\Behavior\Controller;
use Vox\Framework\Behavior\Get;


class FooDto {
    public string $foo;

    public function __construct(string $foo) {
        $this->foo = $foo;
    }
}

/**
 * @Controller("/foo")
 */
class FooController
{
    /**
     * @Get()
     */
    public function get() {
        return new FooDto('bar');
    }
}