<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Hashing\Hasher as HasherContract;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class CustomUserProvider extends EloquentUserProvider
{
    private string $user_email;

    public function __construct(HasherContract $hasher, $model, $method_to_email_model)
    {
        parent::__construct($hasher, $model);

        $this->user_email = $method_to_email_model;
    }

    public function retrieveByCredentials(array $credentials): Model|Builder|Authenticatable|null
    {

        $query = $this->createModel()->newQuery();

        foreach ($credentials as $key => $value) {
            if (Str::contains($key, 'password')) {
                continue;
            }

            $query = User::where('email', $credentials['email'])->first();
        }

        return $query;
    }
}
