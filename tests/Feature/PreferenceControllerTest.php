<?php

namespace Tests\Feature;

use App\Models\Preference;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use PreferenceTypes;

class PreferenceControllerTest extends BaseTestCase
{
    use RefreshDatabase;

    public function test_it_should_return_user_preference_list()
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();
        $token = $user->createToken('API Token')->plainTextToken;

        Preference::factory()->count(5)->create();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/preferences');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'message',
                'data'
            ]);
    }


    public function test_it_should_fetch_single_preference_successfully()
    {
        $user = User::factory()->create();
        $token = $user->createToken('API Token')->plainTextToken;

        $preference = Preference::factory()->create([
            'name' => 'Preference name',
            'type' => PreferenceTypes::SOURCE,
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson("/api/preferences/{$preference->id}");

        $response->assertStatus(200)
            ->assertJson([
                'status' => true,
                'message' => 'Preference retrieved successfully.',
                'data' => [
                    'id' => $preference->id,
                    'name' => 'Preference name',
                    'source' => 'source',
                ],
            ]);
    }

    public function test_fetch_non_existent_preference()
    {
        $user = User::factory()->create();
        $token = $user->createToken('API Token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson("/api/preferences/99999");

        $response->assertStatus(404)
            ->assertJson([
                'status' => false,
                'message' => 'Preference not found',
            ]);
    }

    public function test_it_should_store_preferences()
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();
        $token = $user->createToken('API Token')->plainTextToken;

        $payload = [
            'sources' => ['TechCrunch', 'BBC'],
            'categories' => ['Technology', 'Science'],
            'authors' => ['John Doe', 'Jane Smith'],
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ])->postJson('/api/preferences/store', $payload);

        $response->assertStatus(201)
            ->assertJson([
                'status' => true,
                'message' => 'Preferences saved successfully.',
            ]);

        $this->assertDatabaseHas('preferences', [
            'name' => 'TechCrunch',
            'user_id' => $user->id,
            'type' => PreferenceTypes::SOURCE,
        ]);

        $this->assertDatabaseHas('preferences', [
            'name' => 'Technology',
            'user_id' => $user->id,
            'type' => PreferenceTypes::CATEGORY,
        ]);
    }



}
