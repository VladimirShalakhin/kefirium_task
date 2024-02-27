<?php

namespace App\Services;

use App\Models\User;
use Auth;
use Illuminate\Validation\UnauthorizedException;
use Laravel\Socialite\Facades\Socialite;

class UserService
{
    public function create(string $email, string $password): void
    {
        User::create([
            'email' => $email,
            'password' => $password,
        ]);
    }

    public function createNewToken(string $token): array
    {
        if (! $token) {
            throw new UnauthorizedException();
        }

        $user = Auth::user();

        return ['user' => $user, 'token' => $token];
    }

    public function loginGoogle(): void
    {
        $user = Socialite::driver('google')->user();
        $finduser = User::where('google_id', $user->id)->first();

        if ($finduser) {

            Auth::login($finduser);

        } else {

            $newUser = User::create([
                'email' => $user->email,
                'google_id' => $user->id,
                'password' => encrypt('123456dummy'),

            ]);

            Auth::login($newUser);
        }
    }
}
