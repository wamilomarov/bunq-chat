<?php

namespace App\Action\Conversation;

use App\Renderer\JsonRenderer;
use App\Service\AuthService;
use App\Service\ConversationService;
use App\Service\UserService;
use Exception;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class ConversationSendMessageAction
{
    private JsonRenderer $renderer;
    private ConversationService $conversationService;
    private UserService $userService;

    public function __construct(
        JsonRenderer $jsonRenderer,
        ConversationService $conversationService,
        UserService $userService
    ) {
        $this->renderer = $jsonRenderer;
        $this->conversationService = $conversationService;
        $this->userService = $userService;
    }

    /**
     * @throws \Exception
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $user = AuthService::user($request);
        $data = $request->getParsedBody();

        $username = $data['username'] ?? null;
        $message = $data['message'] ?? null;

        if (!$message || trim($message) == '') {
            throw new Exception("Message should not be empty");
        }

        $receiver = $this->userService->findByUsername($username);
        if (!$receiver) {
            throw new Exception("Receiver not found");
        }

        $conversation = $this->conversationService->firstOrCreate($user->id, $receiver->id);

        $this->conversationService->sendMessage($conversation->id, trim($message), $user->id);

        // Transform result and render to json
        return $this->renderer->json($response, []);
    }
}
