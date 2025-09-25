<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 't@g.com',
            'password' => bcrypt('password'),
        ]);

        $this->call([
            CategorySeeder::class,
            TagSeeder::class,
            BrandSeeder::class,
            DivisionSeeder::class,
            DistrictSeeder::class,
            UpazilaSeeder::class,
            UnionSeeder::class,
            BrandSeeder::class,
        ]);
    }
}
