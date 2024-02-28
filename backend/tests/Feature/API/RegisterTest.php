<?php

namespace API;

use Illuminate\Foundation\Testing\DatabaseTruncation;
use Illuminate\Queue\SerializesModels;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use DatabaseTruncation;
    use SerializesModels;

    public function testRequiresPasswordEmail()
    {
        $this->json('POST', 'api/register')->assertStatus(400)->assertJson([
            'email' => [
                'Почтовый адрес является обязательным полем для заполнения',
            ],
            'password' => [
                'Пароль является обязательным полем для заполнения',
            ]
        ]);
    }

    public function testRegisterSuccessfully()
    {
        $payload = [
            'password' => 'Password123!3434',
            'email' => 'testemail@mail.com',
        ];

        $this->json('post', '/api/register', $payload)->assertStatus(201)->assertJsonStructure([
            'message',
        ]);
    }
}
