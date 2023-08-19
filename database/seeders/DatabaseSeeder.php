<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $local_seeders = [
            \Database\Seeders\Develop\ProductSeeder::class,
            \Database\Seeders\Develop\UserSeeder::class,
            \Database\Seeders\Develop\CustomerSeeder::class,
        ];
        $production_seeders = [
            \Database\Seeders\Production\UserSeeder::class,
        ];
        $this->call([
            ...$production_seeders,
            ...(App::environment('local') ? $local_seeders : []),
        ]);
    }
}
