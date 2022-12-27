<?php

namespace App\Action\User;

use App\Model\User;
use App\Renderer\JsonRenderer;
use App\Service\AuthService;
use App\Service\UserService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class UserListAction
{
    private JsonRenderer $renderer;
    private UserService $userService;

    public function __construct(JsonRenderer $jsonRenderer, UserService $userService)
    {
        $this->renderer = $jsonRenderer;
        $this->userService = $userService;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $data = [];
        $authUser = AuthService::user($request);
        $users = $this->userService->list([$authUser->id]);

        /** @var User $user */
        foreach ($users as $user) {
            $data[] = $user->serializeToJson();
        }

        return $this->renderer->json($response, $data);
    }
}
