<?php

namespace Tests\Feature\API;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTruncation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Queue\SerializesModels;
use Tests\TestCase;

class LogoutTest extends TestCase
{
    use DatabaseTruncation;
    use SerializesModels;

    public function testLogoutSuccess()
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

        $responseInfo = $this->json('post', '/api/login', $payload)->json(['access_token']);
        $token = $responseInfo;
        $headers = ['Authorization' => "Bearer $token"];
        $this->json('post', 'api/logout', [], $headers)->assertStatus(200);
    }
}
