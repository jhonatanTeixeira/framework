<?php


namespace Vox\Framework\Tests\Controller;

use PhpBeans\Factory\ContainerBuilder;
use PhpBeans\Annotation\Autowired;
use Prophecy\Prophecy\ObjectProphecy;
use Slim\Psr7\Factory\ServerRequestFactory;
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

    public function configureBuilder(ContainerBuilder $containerBuilder)
    {
        $containerBuilder->withCache(
            (new Factory)
                ->createSimpleCache(Factory::PROVIDER_DOCTRINE, Factory::TYPE_FILE, 'build/cache')
        );
    }

    public function testShouldGetList() {
//        $data = $this->application->handle((new ServerRequestFactory())->createServerRequest('GET', '/foo'));
        $data = $this->get('/foo');

        $this->assertEquals('[{"foo":"bar"},{"foo":"baz"}]', $data->getBody()->getContents());
    }

    public function testShouldGetOne() {
//        $data = $this->application->handle((new ServerRequestFactory())->createServerRequest('GET', '/foo/0'));
        $data = $this->get('/foo/0');

        $this->assertEquals('{"foo":"bar"}', $data->getBody()->getContents());
    }

    public function testShouldPostOne() {
        $data = $this->post('/foo', ['foo' => 'bar baz']);

        $this->assertEquals('{"foo":"bar baz"}', $data->getBody()->getContents());
    }

    public function testShouldPutOne() {
        $data = $this->put('/foo/2', ['foo' => 'bar bazar']);

        $this->assertEquals('{"foo":"bar bazar"}', $data->getBody()->getContents());
    }

    public function testShouldDeleteOne() {
        $data = $this->delete('/foo/2', ['foo' => 'bar bazar']);

        $this->assertStatus(204, $data);

        $this->assertNotFound($this->get('/foo/2'));
    }

    public function testShouldMockData()
    {
        $this->mockableService->getMockData()->willReturn(['foo' => 'bar']);

        $data = $this->get('/foo/mock');

        $this->assertEquals('{"foo":"bar"}', $data->getBody()->getContents());
    }
}