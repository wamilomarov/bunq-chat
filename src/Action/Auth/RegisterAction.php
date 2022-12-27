<?php

namespace App\Action\Auth;

use App\Renderer\JsonRenderer;
use App\Service\UserService;
use Exception;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class RegisterAction
{
    private JsonRenderer $renderer;
    private UserService $userService;

    public function __construct(JsonRenderer $jsonRenderer, UserService $userService)
    {
        $this->renderer = $jsonRenderer;
        $this->userService = $userService;
    }

    /**
     * @throws Exception
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $data = $request->getParsedBody();
        $user = $this->userService->store($data);

        return $this->renderer->json($response, $user->serializeToJson());
    }
}
