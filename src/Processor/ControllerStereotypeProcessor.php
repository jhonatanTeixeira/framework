<?php


namespace Vox\Framework\Processor;


use Metadata\MetadataFactory;
use PhpBeans\Metadata\ClassMetadata;
use PhpBeans\Processor\AbstractStereotypeProcessor;
use Psr\Http\Message\ResponseInterface;
use Slim\App;
use Slim\Interfaces\RouteInterface;
use Vox\Event\EventDispatcher;
use Vox\Framework\Behavior\Controller;
use Vox\Framework\Behavior\Delete;
use Vox\Framework\Behavior\Get;
use Vox\Framework\Behavior\Interceptor;
use Vox\Framework\Behavior\ParamResolverInterface;
use Vox\Framework\Behavior\Patch;
use Vox\Framework\Behavior\Post;
use Vox\Framework\Behavior\PreDispatch;
use Vox\Framework\Behavior\Put;
use Vox\Framework\Behavior\UseMiddleware;
use Vox\Framework\Collection\CallbackPriorityQueue;
use Vox\Metadata\MethodMetadata;

class ControllerStereotypeProcessor extends AbstractStereotypeProcessor
{
    private MetadataFactory $metadataFactory;

    private EventDispatcher $eventDispatcher;

    public function __construct(MetadataFactory $metadataFactory, EventDispatcher $eventDispatcher) {
        $this->metadataFactory = $metadataFactory;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function getStereotypeName(): string {
        return Controller::class;
    }

    private function processMiddleware(RouteInterface $route, MethodMetadata $methodMetadata,
                                       ClassMetadata $classMetadata) {
        if ($methodMetadata->hasAnnotation(UseMiddleware::class)) {
            $route->add(
                $this->getContainer()
                    ->get($methodMetadata->getAnnotation(UseMiddleware::class)->middlewareClass)
            );
        }

        if ($classMetadata->hasAnnotation(UseMiddleware::class)) {
            $route->add(
                $this->getContainer()
                    ->get($classMetadata->getAnnotation(UseMiddleware::class)->middlewareClass)
            );
        }
    }

    private function parsePath($controller, $method) {
        return '/' . implode(
            '/',
            array_filter(
                array_map(fn($path) => preg_replace('/^\//', '', $path), [$controller->path, $method->path])
            )
        );
    }

    private function getPrioritizedComponents(string $className) {
        return new CallbackPriorityQueue(
            function ($bean1, $bean2) use ($className) {
                $behavior1 = $this->metadataFactory->getMetadataForClass($bean1)->getAnnotation($className);
                $behavior2 = $this->metadataFactory->getMetadataForClass($bean2)->getAnnotation($className);

                return $behavior1->priority <=> $behavior2->priority;
            },
            $this->getContainer()->getBeansByComponent($className)
        );
    }

    public function process($stereotype) {
        /* @var $app App */
        $app = $this->getContainer()->get(App::class);

        /* @var $controllerMetadata ClassMetadata */
        $controllerMetadata = $this->metadataFactory->getMetadataForClass(get_class($stereotype));

        /* @var $config \Vox\Framework\Behavior\Controller */
        $config = $controllerMetadata->getAnnotation(Controller::class);

        $methodMap = [
            Get::class => 'get',
            Post::class => 'post',
            Put::class => 'put',
            Patch::class => 'patch',
            Delete::class => 'delete',
        ];

        /* @var $methodMetadata MethodMetadata */
        foreach ($controllerMetadata->methodMetadata as $methodMetadata) {
            $method = null;
            $methodName = null;

            foreach (array_keys($methodMap) as $currentMethod) {
                if ($methodMetadata->hasAnnotation($currentMethod)) {
                    $method = $methodMetadata->getAnnotation($currentMethod);
                    $methodName = $methodMap[$currentMethod];
                    break;
                }
            }

            if (null === $method || null === $methodName) {
                continue;
            }

            $path = $this->parsePath($config, $method);
            $action = $methodMetadata->reflection->getClosure($stereotype);
            $container = $this->getContainer();

            $routeAction = function ($request, $response, $args) use ($controllerMetadata, $methodMetadata, $action,
                                                                      $container) {
                $params = $args;

                /* @var $resolver ParamResolverInterface */
                foreach ($container->getBeansByComponent(ParamResolverInterface::class) as $resolver) {
                    $params = array_merge(
                        $params,
                        $resolver->resolve($controllerMetadata, $methodMetadata, $request, $args)
                    );
                }

                foreach ($this->getPrioritizedComponents(PreDispatch::class) as $preDispatch) {
                    $params = array_merge($params, $preDispatch($request, $controllerMetadata, $methodMetadata));
                }

                $actionParams = [];

                foreach ($methodMetadata->params as $param) {
                    $actionParams[$param->name] ??= $params[$param->name] ?? null;
                }

                $responseData = call_user_func_array($action, array_values($actionParams));

                if ($responseData instanceof ResponseInterface) {
                    return $responseData;
                }


                foreach ($this->getPrioritizedComponents(Interceptor::class) as $interceptor) {
                    $response = $interceptor($responseData, $request, $response, $args);
                }

                 return $response;
            };

            $route = call_user_func([$app, $methodName], $path, $routeAction);
            $this->processMiddleware($route, $methodMetadata, $controllerMetadata);
        }
    }
}