<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

class AuthControllerTest extends BaseTestCase
{
    use RefreshDatabase;

    public function test_user_can_login_successfully()
    {
        $user = User::factory()->create([
            'password' => bcrypt('12345678'),
        ]);

        $payload = [
            'email' => $user->email,
            'password' => '12345678',
        ];

        $response = $this->postJson('/api/login', $payload);

        $response->assertStatus(200)
            ->assertJson([
                'status' => true,
                'message' => 'User successfully logged in',
            ])
            ->assertJsonStructure([
                'status',
                'message',
                'data' => [
                    'user',
                    'token',
                ],
            ]);
    }

    public function test_login_fails_with_invalid_credentials()
    {
        $user = User::factory()->create([
            'password' => bcrypt('12345678'),
        ]);

        $payload = [
            'email' => $user->email,
            'password' => 'wrongpassword',
        ];

        $response = $this->postJson('/api/login', $payload);

        $response->assertStatus(422)
            ->assertJson([
                'status' => false,
                'message' => 'Invalid credentials.',
            ]);
    }

    public function test_user_can_logout_successfully()
    {
        $user = User::factory()->create();
        $token = $user->createToken('API Token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/logout');

        $response->assertStatus(200)
            ->assertJson([
                'status' => true,
                'message' => 'User successfully logged out',
            ]);
    }

}
