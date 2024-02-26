<?php

namespace App\Services;

use App\Models\User;
use Auth;
use Illuminate\Validation\UnauthorizedException;
use JWTAuth;

class UserService
{
    public function create(string $email, string $password): void
    {
        User::create([
            'email' => $email,
            'password' => $password,
        ]);
    }

    public function createNewToken(string $email, string $password): array
    {
        $token = Auth::attempt(['email' => $email, 'password' => $password]);

        if (! $token) {
            throw new UnauthorizedException();
        }

        $user = Auth::user();

        return ['user' => $user, 'token' => $token];
    }
}
