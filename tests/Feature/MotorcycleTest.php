<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\Category;
use App\Models\City;
use App\Models\Motorcycle;
use App\Models\User;
use Database\Seeders\BrandSeeder;
use Database\Seeders\CategorySeeder;
use Database\Seeders\CitySeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class MotorcycleTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed([BrandSeeder::class, CategorySeeder::class, CitySeeder::class]);
        Storage::fake('public');
    }

    private function validPayload(): array
    {
        return [
            'brand_id'    => Brand::first()->id,
            'category_id' => Category::first()->id,
            'city_id'     => City::first()->id,
            'model'       => 'CBR600RR',
            'year'        => 2021,
            'price'       => 4_500_000,
            'engine_cc'   => 600,
            'mileage'     => 12_000,
        ];
    }

    public function test_guest_sees_only_active_motorcycles(): void
    {
        Motorcycle::factory(3)->create(['status' => 'active']);
        Motorcycle::factory()->create(['status' => 'sold']);

        $this->getJson('/api/motorcycles')
            ->assertOk()
            ->assertJsonCount(3, 'data');
    }

    public function test_guest_cannot_create_motorcycle(): void
    {
        $this->postJson('/api/motorcycles', $this->validPayload())
            ->assertUnauthorized();
    }

    public function test_user_can_create_motorcycle(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $this->postJson('/api/motorcycles', $this->validPayload())
            ->assertCreated()
            ->assertJsonPath('data.status', 'active')
            ->assertJsonPath('data.seller.id', $user->id);

        $this->assertDatabaseHas('motorcycles', [
            'model'   => 'CBR600RR',
            'user_id' => $user->id,
        ]);
    }

    public function test_user_cannot_update_someone_elses_motorcycle(): void
    {
        $motorcycle = Motorcycle::factory()->create(['status' => 'active']);
        Sanctum::actingAs(User::factory()->create());

        $this->patchJson("/api/motorcycles/{$motorcycle->id}", ['status' => 'sold'])
            ->assertForbidden();

        $this->assertDatabaseHas('motorcycles', [
            'id'     => $motorcycle->id,
            'status' => 'active',
        ]);
    }

    public function test_owner_can_delete_motorcycle(): void
    {
        $user       = User::factory()->create();
        $motorcycle = Motorcycle::factory()->for($user)->create();
        Sanctum::actingAs($user);

        $this->deleteJson("/api/motorcycles/{$motorcycle->id}")
            ->assertNoContent();

        $this->assertDatabaseMissing('motorcycles', ['id' => $motorcycle->id]);
    }

    public function test_motorcycles_can_be_filtered_by_price(): void
    {
        Motorcycle::factory()->create(['price' => 1_000_000, 'status' => 'active']);
        Motorcycle::factory()->create(['price' => 3_000_000, 'status' => 'active']);
        Motorcycle::factory()->create(['price' => 6_000_000, 'status' => 'active']);

        $this->getJson('/api/motorcycles?price_min=2000000&price_max=5000000')
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.price', 3_000_000);
    }

    public function test_invalid_price_range_returns_validation_error(): void
    {
        $this->getJson('/api/motorcycles?price_min=5000000&price_max=1000000')
            ->assertUnprocessable()
            ->assertJsonValidationErrors('price_max');
    }
}