<?php

namespace App\Service;

use App\Model\Conversation;
use App\Model\Message;
use App\Model\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class ConversationService
{
    public function list(User $user): Collection
    {
        return $user->conversations()
            ->with(['users', 'lastMessage.sender'])
            ->orderByDesc(Message::query()
                ->select('created_at')
                ->whereColumn('conversations.id', 'messages.conversation_id')
                ->latest())
            ->get();
    }

    public function show(int $conversationId): Conversation|Model|null
    {
        return Conversation::query()
            ->where('id', $conversationId)
            ->with('messages.sender', 'users')
            ->first();
    }

    public function firstOrCreate(int $participant_1, int $participant_2): Conversation
    {
        /** @var Conversation $conversation */
        $conversation = Conversation::query()
            ->whereRelation('users', 'users.id', $participant_1)
            ->whereRelation('users', 'users.id', $participant_2)
            ->firstOrCreate();

        if ($conversation->wasRecentlyCreated) {
            $conversation->users()->sync([$participant_1, $participant_2]);
        }

        return $conversation;
    }

    public function sendMessage(int $conversationId, string $message, int $senderId): void
    {
        Message::query()
            ->create([
                'conversation_id' => $conversationId,
                'message' => $message,
                'sender_id' => $senderId,
            ]);
    }
}
