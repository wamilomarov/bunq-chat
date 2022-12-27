<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

/**
 * @property int $id
 * @property string $username
 * @property string $name
 * @property string $password
 * @property string $token
 * @property Collection $conversations
 */
class User extends Model
{
    protected $fillable = [
        'username',
        'name',
        'password',
        'token',
    ];

    protected $guarded = [
        'password',
        'token',
    ];

    public function conversations(): BelongsToMany
    {
        return $this->belongsToMany(Conversation::class, ConversationUser::class);
    }

    public function serializeToJson(bool $public = true): array
    {
        $userData = [
            'id' => $this->id,
            'username' => $this->username,
            'name' => $this->name,
        ];
        if (!$public) {
            $userData['token'] = $this->token;
        }

        return $userData;
    }
}
