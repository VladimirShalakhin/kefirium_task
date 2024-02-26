<?php

namespace App\Services;

use App\Models\User;

class UserService
{
    public function create(string $email, string $password): void
    {
        User::create([
            'email' => $email,
            'password' => $password,
        ]);
    }
}
