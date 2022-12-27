<?php

namespace App\Service;

use App\Model\User;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class UserService
{
    /**
     * @throws Exception
     */
    public function store(array $data): User|Model
    {
        $existingUser = $this->findByUsername($data['username']);

        if ($existingUser) {
            throw new Exception('The username is already in use.');
        }
        $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT, ['cost' => 12]);

        return User::query()
            ->create($data);
    }

    public function findByUsername(string $username): User|Model|null
    {
        return User::query()
            ->where('username', $username)
            ->first();
    }

    public function list(array $except = []): Collection|array
    {
        return User::query()
            ->whereNotIn('id', $except)
            ->get();
    }
}
