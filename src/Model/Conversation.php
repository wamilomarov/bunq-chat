<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Collection;

/**
 * @property int $id
 * @property Collection|Message[] $messages
 * @property Message $lastMessage
 * @property Collection|User[] $users
 */
class Conversation extends Model
{
    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    public function lastMessage(): HasOne
    {
        return $this->hasOne(Message::class)->latestOfMany();
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, ConversationUser::class);
    }

    public function serializeToJson()
    {
        $data = [
            'id' => $this->id,
        ];
        if ($this->relationLoaded('users')) {
            $data['users'] = [];
            foreach ($this->users as $user) {
                $data['users'][] = $user->serializeToJson();
            }
        }

        if ($this->relationLoaded('lastMessage')) {
            $data['lastMessage'] = $this->lastMessage->serializeToJson();
        }

        if ($this->relationLoaded('messages')){
            $data['messages'] = [];
            foreach ($this->messages as $message) {
                $data['messages'][] = $message->serializeToJson();
            }
        }

        return $data;
    }
}
