<?php

namespace App\Action\Auth;

use App\Renderer\JsonRenderer;
use App\Service\AuthService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class LogoutAction
{
    private JsonRenderer $renderer;
    private AuthService $authService;

    public function __construct(JsonRenderer $jsonRenderer, AuthService $authService)
    {
        $this->renderer = $jsonRenderer;
        $this->authService = $authService;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $this->authService->logout($request);

        return $this->renderer->json($response, []);
    }
}
