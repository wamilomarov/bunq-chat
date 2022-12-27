<?php

namespace App\Action\Conversation;

use App\Renderer\JsonRenderer;
use App\Service\AuthService;
use App\Service\ConversationService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Symfony\Component\Translation\Exception\NotFoundResourceException;

class ConversationShowAction
{
    private JsonRenderer $renderer;
    private ConversationService $conversationService;

    public function __construct(JsonRenderer $jsonRenderer, ConversationService $conversationService)
    {
        $this->renderer = $jsonRenderer;
        $this->conversationService = $conversationService;
    }

    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args
    ): ResponseInterface {
        $conversationId = (int)$args['conversation_id'];
        $user = AuthService::user($request);

        $conversation = $this->conversationService->show($conversationId);

        if (!$conversation || !$conversation->users->contains('username', $user->username)) {
            throw new NotFoundResourceException();
        }
        // Transform result and render to json
        return $this->renderer->json($response, $conversation->serializeToJson());
    }
}
