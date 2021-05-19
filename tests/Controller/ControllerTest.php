<?php


namespace Vox\Framework\Tests\Controller;


use Slim\Psr7\Factory\ServerRequestFactory;
use Vox\Framework\Test\TestCase;

class ControllerTest extends TestCase
{
    public function testShouldGetArray() {
        $data = $this->application->handle((new ServerRequestFactory())->createServerRequest('GET', '/foo'));

        $this->assertEquals('[]', $data->getBody()->getContents());
    }
}