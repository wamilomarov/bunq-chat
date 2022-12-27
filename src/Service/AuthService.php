<?php

namespace App\Service;

use App\Model\User;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Psr\Http\Message\ServerRequestInterface;

class AuthService
{
    /**
     * @throws Exception
     */
    public function login(string $username, string $password): User
    {
        /** @var User|null $user */
        $user = User::query()
            ->where('username', $username)
            ->first();

        if ($user && password_verify($password, $user->password)) {
            return $this->generateToken($user);
        }

        throw new Exception("Invalid credentials");
    }

    public function logout(ServerRequestInterface $request): void
    {
        $user = self::user($request);
        $user->token = null;
        $user->save();
    }

    private function generateToken(User $user): User
    {
        $token = $this->randomString(64);
        if (User::query()->where('token', $token)->exists()) {
            return $this->generateToken($user);
        } else {
            $user->token = $token;
            $user->save();
            return $user;
        }
    }

    private function randomString(int $length): string
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';

        for ($i = 0; $i < $length; $i++) {
            $index = rand(0, strlen($characters) - 1);
            $randomString .= $characters[$index];
        }
        return $randomString;
    }

    public static function user(ServerRequestInterface $request): Model|User|null
    {
        $token = self::getBearerToken($request);
        if (!is_null($token)) {
            return self::validateToken($token);
        }
        return null;
    }

    private static function getBearerToken(ServerRequestInterface $request): ?string
    {
        $headers = $request->getHeaderLine('Authorization');
        if (!empty($headers)) {
            if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
                return $matches[1];
            }
        }
        return null;
    }

    private static function validateToken(string $token): User|Model|null
    {
        return User::query()
            ->where('token', $token)
            ->first();
    }
}
