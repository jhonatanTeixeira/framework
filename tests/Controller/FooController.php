<?php


namespace Vox\Framework\Tests\Controller;

use PhpBeans\Annotation\Autowired;
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

#[Controller('/foo')]
class FooController
{
    #[Autowired]
    private FooService $service;

    #[Autowired]
    private MockableService $mockableService;

    #[Get('/mock')]
    public function getMockData() {
        return $this->mockableService->getMockData();
    }

    #[Get]
    public function list() {
        return $this->service->list();
    }

    /**
     * @Get("/{id}")
     */
    #[Get('/{id}')]
    public function get($id) {
        return $this->service->get($id);
    }

    #[Post]
    public function post(FooDto $data) {
        return $this->service->post($data);
    }

    #[Put('{id}')]
    public function put($id, FooDto $data) {
        return $this->service->put($id, $data);
    }

    #[Delete]
    public function delete($id) {
        $this->service->delete($id);
    }
}
