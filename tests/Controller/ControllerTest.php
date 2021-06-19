<?php


namespace Vox\Framework\Tests\Controller;

use Prophecy\Prophecy\ObjectProphecy;
use Slim\Psr7\Factory\ServerRequestFactory;
use Slim\Psr7\Factory\StreamFactory;
use Vox\Framework\Application;
use Vox\Framework\Test\Behavior\Mock;
use Vox\Framework\Test\TestCase;

class ControllerTest extends TestCase
{
    /**
     * @Mock(MockableService::class)
     * @var MockableService|ObjectProphecy
     */
    private ObjectProphecy $mockableService;
    
    public function setupApplication(Application $application) {
        $application->addNamespaces('Vox\Framework\Tests\\');
    }

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

    public function testShouldMockData()
    {
        $this->mockableService->getMockData()->willReturn(['foo' => 'bar']);

        $data = $this->application->handle(
            (new ServerRequestFactory())
                ->createServerRequest('GET', '/foo/mock')
        );

        $this->assertEquals('{"foo":"bar"}', $data->getBody()->getContents());
    }
}