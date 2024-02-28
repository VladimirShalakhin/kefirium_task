<?php

namespace Tests\Feature\API;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTruncation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Queue\SerializesModels;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use DatabaseTruncation;
    use SerializesModels;

    public function testRequiresPasswordValue()
    {
        $this->json('POST', 'api/login')->assertStatus(400)->assertJson([
            'email' => [
                'Почтовый адрес является обязательным полем для заполнения',
            ],
            'password' => [
                'Пароль является обязательным полем для заполнения',
            ],
        ]);
    }

    public function testLoginSuccessfully()
    {
        User::factory()->create([
            'email' => 'testemail@mail.com',
            'password' => 'Password123!3434',
        ]);

        $payload = [
            'password' => 'Password123!3434',
            'email' => 'testemail@mail.com',
        ];

        $this->json('post', '/api/login', $payload)->assertStatus(200)->assertJsonStructure([
            'user' => [
                'id',
                'email',
            ]
        ]);
    }

    public function testLoginWrongPassword()
    {
        User::factory()->create([
            'email' => 'testemail@mail.com',
            'password' => 'Password123!3434',
        ]);

        $payload = [
            'password' => 'Password123!3434!',
            'email' => 'testemail@mail.com',
        ];

        $this->json('post', '/api/login', $payload)->assertStatus(401)->assertJsonStructure([
            'message'
        ]);
    }

}
