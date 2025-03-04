<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

class ArticleControllerTest extends BaseTestCase
{
    use RefreshDatabase;

    public function test_it_should_return_articles_list()
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();
        $token = $user->createToken('API Token')->plainTextToken;

        Article::factory()->count(5)->create();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/articles');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'message',
                'data'
            ]);
    }

    public function test_it_should_filter_articles_by_keyword()
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();
        $token = $user->createToken('API Token')->plainTextToken;

        Article::factory()->create(['title' => 'Laravel']);
        Article::factory()->create(['title' => 'React']);
        Article::factory()->create(['title' => 'Javascript']);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/articles?q=Laravel');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'message',
                'data',
            ])
            ->assertJsonCount(1, 'data')
            ->assertJsonFragment(['title' => 'Laravel']);
    }

    public function test_it_should_fetch_single_article_successfully()
    {
        $user = User::factory()->create();
        $token = $user->createToken('API Token')->plainTextToken;

        $article = Article::factory()->create([
            'title' => 'Article Title',
            'description' => 'This is for testing.',
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson("/api/articles/{$article->id}");

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'message' => 'Article retrieved successfully',
                'data' => [
                    'id' => $article->id,
                    'title' => 'Article Title',
                    'description' => 'This is for testing.',
                ],
            ]);
    }

    public function test_fetch_non_existent_article()
    {
        $user = User::factory()->create();
        $token = $user->createToken('API Token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson("/api/articles/99999");

        $response->assertStatus(404)
            ->assertJson([
                'status' => false,
                'message' => 'Article not found',
            ]);
    }

}
