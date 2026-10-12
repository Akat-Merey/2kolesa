<?php

namespace Database\Seeders;

use App\Models\City;
use Illuminate\Database\Seeder;

class CitySeeder extends Seeder
{
    public function run(): void
    {
        $cities = ['Астана', 'Алматы', 'Шымкент', 'Караганда', 'Актобе', 'Павлодар'];

        foreach ($cities as $name) {
            City::create(['name' => $name]);
        }
    }
}
