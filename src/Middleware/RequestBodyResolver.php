<?php


namespace Vox\Framework\Middleware;

use PhpBeans\Metadata\ClassMetadata;
use Psr\Http\Message\ServerRequestInterface;
use Vox\Data\Serializer;
use Vox\Framework\Behavior\ParamResolverInterface;
use Vox\Framework\Behavior\RequestBody;
use Vox\Metadata\MethodMetadata;

class RequestBodyResolver implements ParamResolverInterface
{
    private Serializer $serializer;

    private string $defaultFormat;

    public function __construct(Serializer $serializer, string $defaultFormat = 'json')
    {
        $this->serializer = $serializer;
        $this->defaultFormat = $defaultFormat;
    }

    public function resolve(ClassMetadata $controllerMetadata, MethodMetadata $methodMetadata,
                            ServerRequestInterface $request, array $args): array {
        if (!in_array($request->getMethod(), ['POST', 'PUT', 'PATCH'])) {
            return [];
        }

        /* @var $paramsMetadata \Vox\Metadata\ParamMetadata[] */
        $paramsMetadata = [];
        $params = [];

        foreach ($methodMetadata->params as $param) {
            $paramsMetadata[$param->name] = $param;
        }

        $type = null;

        if ($requestBody = $methodMetadata->getAnnotation(RequestBody::class)) {
            $argName = $requestBody->argName;
            $type = $requestBody->type;
        } else {
            $argName = reset($paramsMetadata)->name;
        }

        if ($paramType = $paramsMetadata[$argName]->type) {
            $type = $paramType;
        }

        $body = $this->serializer
            ->deserialize($this->defaultFormat, $type, $request->getBody());

        return [$argName => $body];
    }
}