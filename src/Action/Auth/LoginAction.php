<?php

namespace App\Action\Auth;

use App\Renderer\JsonRenderer;
use App\Service\AuthService;
use Exception;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class LoginAction
{
    private JsonRenderer $renderer;
    private AuthService $authService;

    public function __construct(JsonRenderer $jsonRenderer, AuthService $authService)
    {
        $this->renderer = $jsonRenderer;
        $this->authService = $authService;
    }

    /**
     * @throws Exception
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $data = $request->getParsedBody();
        $username = $data['username'];
        $password = $data['password'];

        $user = $this->authService->login($username, $password);

        return $this->renderer->json($response, $user->serializeToJson(false));
    }
}
