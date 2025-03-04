<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

class RegisterControllerTest extends BaseTestCase
{
    use RefreshDatabase;

    public function test_user_can_register_successfully()
    {
        $payload = [
            'name' => 'Test user',
            'email' => 'testuser@gmail.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        $response = $this->postJson('/api/register', $payload);

        $response->assertStatus(201)
            ->assertJson([
                'status' => true,
                'message' => 'User Registered Successfully!',
            ])
            ->assertJsonStructure([
                'status',
                'message',
                'data' => [
                    'user',
                    'token',
                ],
            ]);

        $this->assertDatabaseHas('users', [
            'email' => 'testuser@gmail.com',
        ]);
    }

    public function test_registration_fails_with_missing_fields()
    {
        $payload = [];

        $response = $this->postJson('/api/register', $payload);

        $response->assertStatus(422)
            ->assertJson([
                'status' => false,
                'message' => 'Validation Error.',
            ])
            ->assertJsonStructure([
                'error' => [
                    'name',
                    'email',
                    'password',
                ],
            ]);
    }

    public function test_registration_fails_with_duplicate_email()
    {
        User::factory()->create([
            'email' => 'testuser@gmail.com',
        ]);

        $payload = [
            'name' => 'Test User',
            'email' => 'testuser@gmail.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        $response = $this->postJson('/api/register', $payload);

        $response->assertStatus(422)
            ->assertJson([
                'status' => false,
                'message' => 'Validation Error.',
            ])
            ->assertJsonStructure([
                'error' => [
                    'email',
                ],
            ]);
    }

    public function test_registration_fails_with_unconfirmed_password()
    {
        $payload = [
            'name' => 'Test user',
            'email' => 'testuser@gmail.com',
            'password' => 'password123',
            'password_confirmation' => 'wrongpassword',
        ];

        $response = $this->postJson('/api/register', $payload);

        $response->assertStatus(422)
            ->assertJson([
                'status' => false,
                'message' => 'Validation Error.',
            ])
            ->assertJsonStructure([
                'error' => [
                    'password',
                ],
            ]);
    }

}
