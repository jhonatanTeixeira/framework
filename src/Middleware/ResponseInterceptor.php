<?php


namespace Vox\Framework\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Psr7\Factory\ResponseFactory;
use Vox\Framework\Behavior\Middleware;

/**
 * @Middleware
 */
class ResponseInterceptor
{
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next) {
        $response = $next($request, $response);

        if (!$response instanceof ResponseInterface) {
            $response = ResponseFactory::createResponse(200)->withBody(json_encode($response));
        }

        return $response;
    }
}