<?php

namespace Vox\Framework\Component;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Throwable;
use Vox\Framework\Behavior\ErrorHandler;
use Vox\Framework\Exception\HttpNotFoundException;

/**
 * @ErrorHandler()
 */
class HttpErrorHandler {
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, Throwable $error) {
        switch (get_class($error)) {
            case HttpNotFoundException::class:
                return $response->withStatus(404, $error->getMessage());
        }
    }
}
