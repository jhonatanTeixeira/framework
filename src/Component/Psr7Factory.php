<?php


namespace Vox\Framework\Component;

use PhpBeans\Annotation\Component;
use Slim\Psr7\Factory\ResponseFactory;
use Slim\Psr7\Factory\StreamFactory;
use Vox\Data\Serializer;

/**
 * @Component()
 */
class Psr7Factory
{
    private ResponseFactory $responseFactory;

    private StreamFactory $streamFactory;

    private Serializer $serializer;

    public function __construct(ResponseFactory $responseFactory, StreamFactory $streamFactory, Serializer $serializer)
    {
        $this->responseFactory = $responseFactory;
        $this->streamFactory = $streamFactory;
        $this->serializer = $serializer;
    }

    public function createResponse(int $status, $body, $format = 'json') {
        if ($body instanceof \Throwable) {
            $body = (string) $body;
        }

        switch (gettype($body)) {
            case 'string':
            case 'integer':
            case 'double':
            case 'boolean':
                $stream = $this->streamFactory->createStream($body);
                break;
            case 'resource':
                $stream = $this->streamFactory->createStreamFromResource($body);
                break;
            case "NULL":
                return $this->responseFactory->createResponse(204);
            default:
                $stream = $this->streamFactory->createStream($this->serializer->serialize($format, $body));
        }

        return $this->responseFactory->createResponse($status)->withBody($stream);
    }
}