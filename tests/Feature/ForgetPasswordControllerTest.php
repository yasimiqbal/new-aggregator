<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

class ForgetPasswordControllerTest extends BaseTestCase
{
    use RefreshDatabase;

    public function test_user_can_request_password_reset_link_successfully()
    {
        $user = User::factory()->create([
            'email' => 'testuser@gmail.com',
        ]);

        $payload = ['email' => 'testuser@gmail.com'];

        $response = $this->postJson('/api/password/forgot', $payload);

        $response->assertStatus(200)
            ->assertJson([
                'status' => true,
                'message' => 'Password reset link sent successfully to your email address.',
            ]);

        $this->assertDatabaseHas('password_reset_tokens', [
            'email' => 'testuser@gmail.com',
        ]);
    }

    public function test_password_reset_request_fails_for_non_existent_user()
    {
        $payload = ['email' => 'nonexistent@example.com'];

        $response = $this->postJson('/api/password/forgot', $payload);

        $response->assertStatus(422)
            ->assertJson([
                'message' => 'The selected email is invalid.',
                'errors' => [
                    'email' => ['The selected email is invalid.']
                ],
            ]);


        $this->assertDatabaseMissing('password_reset_tokens', [
            'email' => 'nonexistent@example.com',
        ]);
    }

    public function test_password_reset_request_fails_with_invalid_email_format()
    {
        $payload = ['email' => 'invalid-email-format'];

        $response = $this->postJson('/api/password/forgot', $payload);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

}
