<?php

namespace App\Action\Conversation;

use App\Model\Conversation;
use App\Renderer\JsonRenderer;
use App\Service\AuthService;
use App\Service\ConversationService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class ConversationListAction
{
    private JsonRenderer $renderer;
    private ConversationService $conversationService;

    public function __construct(JsonRenderer $jsonRenderer, ConversationService $conversationService)
    {
        $this->renderer = $jsonRenderer;
        $this->conversationService = $conversationService;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $user = AuthService::user($request);

        $conversations = $this->conversationService->list($user);
        $data = [];
        /** @var Conversation $conversation */
        foreach ($conversations as $conversation) {
            $data[] = $conversation->serializeToJson();
        }
        return $this->renderer->json($response, $data);
    }

}
