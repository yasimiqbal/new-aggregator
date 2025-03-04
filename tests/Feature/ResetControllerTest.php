<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

class ResetControllerTest extends BaseTestCase
{
    use RefreshDatabase;

    public function test_user_can_reset_password_successfully()
    {
        $user = User::factory()->create([
            'email' => 'testuser@gmail.com',
            'password' => bcrypt('oldpassword'),
        ]);

        $token = Password::createToken($user);

        DB::table('password_reset_tokens')->where('email', $user->email)->delete();

        DB::table('password_reset_tokens')->insert([
            'email' => $user->email,
            'token' => $token,
            'created_at' => now(),
        ]);

        $payload = [
            'email' => 'testuser@gmail.com',
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
            'token' => $token,
        ];

        $response = $this->postJson('/api/password/reset', $payload);

        $response->assertStatus(200)
            ->assertJson([
                'status' => true,
                'message' => 'Password reset successfully.',
            ]);

        $this->assertTrue(Hash::check('newpassword123', $user->fresh()->password));
    }

    public function test_password_reset_fails_with_invalid_token()
    {
        $user = User::factory()->create([
            'email' => 'testuser@gmail.com',
            'password' => bcrypt('oldpassword'),
        ]);

        $payload = [
            'email' => 'testuser@gmail.com',
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
            'token' => 'invalid-token',
        ];

        $response = $this->postJson('/api/password/reset', $payload);

        $response->assertStatus(400)
            ->assertJson([
                'status' => false,
                'message' => 'Invalid or expired token.',
            ]);
    }

    public function test_password_reset_fails_with_missing_fields()
    {
        $payload = [];
        $response = $this->postJson('/api/password/reset', $payload);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email', 'password', 'token']);
    }

    public function test_password_reset_fails_when_passwords_do_not_match()
    {
        $user = User::factory()->create([
            'email' => 'testuser@gmail.com',
        ]);

        $token = Password::createToken($user);

        $payload = [
            'email' => 'testuser@gmail.com',
            'password' => 'newpassword123',
            'password_confirmation' => 'mismatchedpassword',
            'token' => $token,
        ];

        $response = $this->postJson('/api/password/reset', $payload);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['password']);
    }
}
