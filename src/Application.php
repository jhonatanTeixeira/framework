<?php

namespace Vox\Framework;

use PhpBeans\Container\Container;
use PhpBeans\Factory\ContainerBuilder;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\App;
use Slim\Factory\AppFactory;
use Slim\Psr7\Factory\ResponseFactory;
use Slim\Psr7\Factory\StreamFactory;
use Vox\Data\ObjectExtractor;
use Vox\Data\ObjectHydrator;
use Vox\Data\Serializer;
use Vox\Framework\Behavior\Controller;
use Vox\Framework\Behavior\Interceptor;
use Vox\Framework\Behavior\Middleware;
use Vox\Framework\Behavior\PreDispatch;
use Vox\Framework\Behavior\Service;
use Vox\Metadata\Factory\MetadataFactoryFactory;

class Application
{
    private ?ContainerBuilder $builder = null;

    private ?Container $container = null;

    public function configure(callable $configure = null) {
        $builder = new ContainerBuilder();

        $builder->withStereotypes(
            Controller::class,
            Service::class,
            Middleware::class,
            PreDispatch::class,
            Interceptor::class
        )->withBeans([
            App::class => AppFactory::create(),
            ResponseFactory::class => new ResponseFactory(),
            StreamFactory::class => new StreamFactory(),
        ])->withComponents(
            ObjectExtractor::class,
            ObjectHydrator::class,
            Serializer::class,
        );

        if ($configure) {
            $configure($builder);
        }

        $this->builder = $builder;
    }

    public function getBuilder(): ?ContainerBuilder
    {
        return $this->builder;
    }

    public function getContainer(): Container {
        return $this->container ?? $this->container = $this->builder->build();
    }

    public function run() {
        if (!$this->builder) {
            $this->configure();
        }

        $container = $this->getContainer();
        $container->get(App::class)->run();
    }

    public function handle(ServerRequestInterface $request): ResponseInterface {
        $app = $this->getContainer()->get(App::class);

        return $app->handle($request);
    }
}