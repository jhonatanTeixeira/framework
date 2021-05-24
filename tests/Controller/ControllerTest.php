<?php


namespace Vox\Framework\Tests\Controller;


use Slim\Psr7\Factory\ServerRequestFactory;
use Slim\Psr7\Factory\StreamFactory;
use Vox\Framework\Test\TestCase;

class ControllerTest extends TestCase
{
    public function testShouldGetList() {
        $data = $this->application->handle((new ServerRequestFactory())->createServerRequest('GET', '/foo'));

        $this->assertEquals('[{"foo":"bar"},{"foo":"baz"}]', $data->getBody()->getContents());
    }

    public function testShouldGetOne() {
        $data = $this->application->handle((new ServerRequestFactory())->createServerRequest('GET', '/foo/0'));

        $this->assertEquals('{"foo":"bar"}', $data->getBody()->getContents());
    }

    public function testShouldPostOne() {
        $data = $this->application->handle(
            (new ServerRequestFactory())
                ->createServerRequest('POST', '/foo')
                ->withBody((new StreamFactory())->createStream('{"foo": "bar baz"}'))
        );

        $this->assertEquals('{"foo":"bar baz"}', $data->getBody()->getContents());
    }
}