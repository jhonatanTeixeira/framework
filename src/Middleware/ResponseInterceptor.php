<?php


namespace Vox\Framework\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Psr7\Factory\ResponseFactory;
use Slim\Psr7\Stream;
use Vox\Framework\Behavior\Interceptor;

/**
 * @Interceptor()
 */
class ResponseInterceptor
{
    public function __invoke($responseData, ServerRequestInterface $request, ResponseInterface $response, array $args) {
        $response->getBody()->write(json_encode($responseData));
    }
}