<?php

namespace App\Model;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $conversation_id
 * @property int $sender_id
 * @property string $message
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property User $sender
 * @property Conversation $conversation
 */
class Message extends Model
{
    protected $fillable = [
        'conversation_id',
        'sender_id',
        'message',
    ];

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function conversation(): BelongsTo
    {
        return $this->belongsTo(Conversation::class);
    }

    public function serializeToJson(): array
    {
        $data = [
            'id' => $this->id,
            'message' => $this->message,
            'created_at' => $this->created_at->toDateTimeString(),
        ];

        if ($this->relationLoaded('sender')) {
            $data['sender'] = $this->sender->serializeToJson();
        } else {
            $data['sender_id'] = $this->sender_id;
        }

        return $data;
    }
}
