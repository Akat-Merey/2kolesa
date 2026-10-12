<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Motorcycle;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $this->call([
            BrandSeeder::class,
            CategorySeeder::class,
            CitySeeder::class,
        ]);

        $testUser = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
        $users = User::factory(9)->create()->push($testUser);

        Motorcycle::factory(50)->recycle($users)->create();

        foreach ($users as $user) {
            $user->favorites()->attach(
                Motorcycle::inRandomOrder()->limit(3)->pluck('id')
            );
        }
    }
}
