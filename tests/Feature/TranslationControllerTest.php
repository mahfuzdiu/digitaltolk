<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class TranslationControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_translations_search(): void
    {
        $user = User::factory()->create();

        // 3. Act as the user using Sanctum's helper
        $response = $this->actingAs($user, 'sanctum')
            ->getJson('/api/translations?locale=en');

        // 4. Assertions
        $response->assertStatus(200)
            ->assertJsonStructure([
                'data',
                'to',
                'total'
            ]);

        $response->assertStatus(200);
    }
}
