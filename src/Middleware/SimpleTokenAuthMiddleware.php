<?php

namespace App\Middleware;

use App\Service\AuthService;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class SimpleTokenAuthMiddleware implements MiddlewareInterface
{
    private ResponseFactoryInterface $responseFactory;

    public function __construct(ResponseFactoryInterface $responseFactory)
    {
        $this->responseFactory = $responseFactory;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $user = AuthService::user($request);
        if ($user) {
            return $handler->handle($request);
        }
        return $this->unauthenticated();
    }

    private function unauthenticated(): ResponseInterface
    {
        $response = $this->responseFactory->createResponse();
        $response->getBody()
            ->write('Unauthenticated');
        return $response->withStatus(401);
    }
}
