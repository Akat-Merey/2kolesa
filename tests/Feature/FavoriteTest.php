<?php

namespace Tests\Feature;

use App\Models\Motorcycle;
use App\Models\User;
use Database\Seeders\BrandSeeder;
use Database\Seeders\CategorySeeder;
use Database\Seeders\CitySeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class FavoriteTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed([BrandSeeder::class, CategorySeeder::class, CitySeeder::class]);
    }

    public function test_adding_to_favorites_is_idempotent(): void
    {
        $motorcycle = Motorcycle::factory()->create();
        Sanctum::actingAs(User::factory()->create());

        $this->postJson("/api/motorcycles/{$motorcycle->id}/favorite")->assertNoContent();
        $this->postJson("/api/motorcycles/{$motorcycle->id}/favorite")->assertNoContent();

        $this->assertDatabaseCount('favorites', 1);
    }

    public function test_user_sees_only_own_favorites(): void
    {
        $user  = User::factory()->create();
        $other = User::factory()->create();
        $mine  = Motorcycle::factory()->create();
        $their = Motorcycle::factory()->create();

        $user->favorites()->attach($mine);
        $other->favorites()->attach($their);

        Sanctum::actingAs($user);

        $this->getJson('/api/me/favorites')
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.id', $mine->id);
    }
}