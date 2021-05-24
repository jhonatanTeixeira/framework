<?php


namespace Vox\Framework\Tests\Controller;

use Vox\Framework\Behavior\Controller;
use Vox\Framework\Behavior\Delete;
use Vox\Framework\Behavior\Get;
use Vox\Framework\Behavior\Post;
use Vox\Framework\Behavior\Put;


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
    private array $data;

    public function __construct()
    {
        $this->data = [
            new FooDto('bar'),
            new FooDto('baz'),
        ];
    }


    /**
     * @Get()
     */
    public function list() {
        return $this->data;
    }

    /**
     * @Get("/{id}")
     */
    public function get($id) {
        return $this->data[$id];
    }

    /**
     * @Post()
     */
    public function post(FooDto $data) {
        return $this->data[] = $data;
    }

    /**
     * @Put("{id}")
     */
    public function put($id, FooDto $data) {
        return $this->data[$id] = $data;
    }

    /**
     * @Delete("{id}")
     */
    public function delete($id) {
        $this->data = array_filter($this->data, fn($index) => $id == $index, ARRAY_FILTER_USE_KEY);
    }
}