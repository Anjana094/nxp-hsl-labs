<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Provider;
use App\Models\User; 

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create provider only once
        $provider = Provider::firstOrCreate(
            ['email' => 'anjana@nxp.com'],
            ['name' => 'Anjana Rani']
        );

        // Create inventory only once
        $provider->inventory()->firstOrCreate(
            [],
            ['stock' => 100]
        );

        // Create user linked to provider (for Sanctum login)
        User::firstOrCreate(
            ['email' => 'anjana@nxp.com'],
            [
                'name' => 'Anjana Rani',
                'password' => bcrypt('password'),
                'provider_id' => $provider->id
            ]
        );
    }
}
