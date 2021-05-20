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
    public function __invoke(ServerRequestInterface $request, $data, array $args) {
        $response = (new ResponseFactory())->createResponse();

        $response->getBody()->write(json_encode($data));

        return $response;
    }
}