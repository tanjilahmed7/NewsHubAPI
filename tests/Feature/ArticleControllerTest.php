<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\User;
use App\Models\UserPreference;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ArticleControllerTest extends TestCase
{
    use RefreshDatabase;


    /**
     * Test case for testing the functionality of returning paginated articles.
     *
     * @return void
     */
    public function test_it_returns_paginated_articles()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        Article::factory()->count(15)->create();

        $response = $this->getJson('/api/articles');

        $response->assertStatus(200)
            ->assertJsonCount(10, 'data') // Checks there are 10 items in the 'data' array
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'source_id',
                        'source_name',
                        'author',
                        'title',
                        'description',
                        'url',
                        'url_to_image',
                        'published_at',
                        'content',
                        'created_at',
                        'updated_at',
                    ],
                ],
                'current_page',
                'first_page_url',
                'from',
                'last_page',
                'last_page_url',
                'links',
                'next_page_url',
                'path',
                'per_page',
                'prev_page_url',
                'to',
                'total',
            ]);
    }


    /**
     * Test case for testing the functionality of returning a single article.
     *
     * @return void
     */
    public function test_it_returns_a_single_article()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $article = Article::factory()->create();

        $response = $this->getJson("/api/articles/{$article->id}");

        $response->assertStatus(200)
            ->assertJson(['id' => $article->id, 'title' => $article->title]);
    }

    /**
     * Test case for filtering articles based on criteria.
     *
     * @return void
     */
    public function test_it_filters_articles_based_on_criteria()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        // Create a matching article
        Article::factory()->create([
            'title' => 'Hello World',
            'source_name' => 'Hello Source',
            'published_at' => now()->subDays(5),
        ]);

        // Create a non-matching article
        Article::factory()->create([
            'title' => 'Test Article',
            'source_name' => 'Other',
            'published_at' => now()->subDays(2),
        ]);

        // Define the query parameters for filtering
        $params = [
            'keyword' => 'Hello World',
            'source' => 'Hello Source',
            'from' => now()->subWeek()->toDateString(),
            'to' => now()->toDateString(),
        ];

        $response = $this->getJson('/api/filtered?' . http_build_query($params));


        $response->assertStatus(200)
            ->assertJsonCount(1, 'data') // Ensure only one article is returned
            ->assertJsonFragment(['title' => 'Hello World']); // Check that the correct article is returned
    }


    /**
     * Test case for the `test_personalized_news_returns_404_when_preferences_not_found` method.
     *
     * @return void
     */
    public function test_personalized_news_returns_404_when_preferences_not_found()
    {
        // Create a user
        $user = User::factory()->create();

        // Act as the user and hit the endpoint
        $response = $this->actingAs($user)->getJson('/api/personalized-news');

        // Assert that a 404 status is returned
        $response->assertStatus(404)
            ->assertJson(['message' => 'User preferences not found']);
    }

    /**
     * Test the functionality of filtering personalized news by sources.
     *
     * @return void
     */
    public function test_personalized_news_filters_by_sources()
    {
        // Create a user and their preferences
        $user = User::factory()->create();
        UserPreference::create([
            'user_id' => $user->id,
            'sources' => ['BBC News'], // Make sure this is an array to match the preferences model
            'authors' => ['John Doe'],
        ]);

        // Create articles
        Article::factory()->create(['source_name' => 'BBC News', 'title' => 'Article 1', 'author' => 'John Doe']);
        Article::factory()->create(['source_name' => 'CNN', 'title' => 'Article 2', 'author' => 'John Doe']);

        // Act as the user and hit the endpoint
        $response = $this->actingAs($user)->getJson('/api/personalized-news');

        // Assert that only articles from BBC News are returned
        $response->assertStatus(200);
        $this->assertCount(1, $response->json('data')); // Now expects only 1 article
        $this->assertEquals('BBC News', $response->json('data')[0]['source_name']);
    }
}
