<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\Preference;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use PreferenceTypes;

class NewsFeedControllerTest extends BaseTestCase
{
   use RefreshDatabase;

    public function test_it_should_return_user_news_feed_successfully()
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();
        $token = $user->createToken('API Token')->plainTextToken;

        Preference::factory()->create([
            'user_id' => $user->id,
            'name' => 'TechCrunch',
            'type' => PreferenceTypes::SOURCE,
        ]);

        Preference::factory()->create([
            'user_id' => $user->id,
            'name' => 'Technology',
            'type' => PreferenceTypes::CATEGORY,
        ]);

        Article::factory()->create([
            'title' => 'Latest Tech News',
            'source' => 'TechCrunch',
            'category' => 'Technology',
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/news/feed');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'message',
                'data'
            ]);
    }

    public function test_it_should_return_error_when_user_has_no_preferences()
    {
        $user = User::factory()->create();
        $token = $user->createToken('API Token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ])->getJson('/api/news/feed');

        $response->assertStatus(404)
            ->assertJson([
                'status' => false,
                'message' => 'No preferences found for this user.',
            ]);
    }
}
