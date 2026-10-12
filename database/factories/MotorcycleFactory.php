<?php

namespace Database\Factories;

use App\Models\Brand;
use App\Models\Category;
use App\Models\City;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class MotorcycleFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'brand_id' => Brand::inRandomOrder()->value('id'),
            'category_id' => Category::inRandomOrder()->value('id'),
            'city_id' => City::inRandomOrder()->value('id'),
            'model' => strtoupper(fake()->bothify('??-###')),
            'year' => fake()->numberBetween(2005, 2026),
            'price' => fake()->numberBetween(300_000, 15_000_000),
            'engine_cc' => fake()->randomElement([50, 125, 250, 400, 600, 750, 1000, 1200]),
            'mileage' => fake()->numberBetween(0, 80_000),
            'description' => fake()->optional()->paragraph(),
            'status' => fake()->randomElement(['active', 'active', 'active', 'sold']),
        ];
    }
}
