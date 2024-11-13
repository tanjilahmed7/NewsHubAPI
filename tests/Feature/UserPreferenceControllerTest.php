<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\UserPreference;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserPreferenceControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test setting user preferences.
     *
     * @return void
     */
    public function test_user_can_set_preferences()
    {
        // Create a user
        $user = User::factory()->create();

        // Simulate login to get the token
        $this->actingAs($user, 'sanctum');

        // Define preference data
        $preferenceData = [
            'sources' => ['bbc-news', 'the-guardian'],
            'categories' => ['technology', 'health'],
            'authors' => ['john-doe', 'jane-smith'],
        ];

        // Send POST request to set preferences
        $response = $this->postJson('/api/preferences', $preferenceData);

        // Assert that the preferences were created successfully
        $response->assertStatus(200)
            ->assertJson([
                'user_id' => $user->id,
                'sources' => $preferenceData['sources'],
                'categories' => $preferenceData['categories'],
                'authors' => $preferenceData['authors'],
            ]);

        // Check that the data was stored in the database
        $this->assertDatabaseHas('user_preferences', [
            'user_id' => $user->id,
            'sources' => json_encode($preferenceData['sources']),
            'categories' => json_encode($preferenceData['categories']),
            'authors' => json_encode($preferenceData['authors']),
        ]);
    }

    /**
     * Test retrieving user preferences.
     *
     * @return void
     */
    public function test_user_can_retrieve_preferences()
    {
        // Create a user and a set of preferences for the user
        $user = User::factory()->create();
        $preferenceData = [
            'user_id' => $user->id,
            'sources' => ['bbc-news', 'the-guardian'],
            'categories' => ['technology', 'health'],
            'authors' => ['john-doe', 'jane-smith'],
        ];
        UserPreference::create($preferenceData);

        // Simulate login to get the token
        $this->actingAs($user, 'sanctum');

        // Send GET request to retrieve preferences
        $response = $this->getJson('/api/preferences');

        // Assert that the preferences were retrieved successfully
        $response->assertStatus(200)
            ->assertJson([
                'user_id' => $user->id,
                'sources' => $preferenceData['sources'],
                'categories' => $preferenceData['categories'],
                'authors' => $preferenceData['authors'],
            ]);
    }

    /**
     * Test validation errors when setting preferences.
     *
     * @return void
     */
    public function test_validation_errors_when_setting_preferences()
    {
        // Create a user
        $user = User::factory()->create();

        // Simulate login
        $this->actingAs($user, 'sanctum');

        // Send invalid preference data (non-array values for sources, categories, authors)
        $invalidData = [
            'sources' => 'invalid-source',    // Should be an array
            'categories' => 'invalid-category', // Should be an array
            'authors' => 'invalid-author',    // Should be an array
        ];

        // Send POST request
        $response = $this->postJson('/api/preferences', $invalidData);

        // Assert validation errors for each field
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['sources', 'categories', 'authors']);
    }
}
