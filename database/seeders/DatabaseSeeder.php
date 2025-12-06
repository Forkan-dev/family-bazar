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
        // Create admin user
        $admin = User::factory()->create([
            'name' => 'Admin User',
            'email' => 't@g.com',
            'password' => bcrypt('password'),
        ]);

        // Create shop owner user
        $shopOwnerUser = User::factory()->create([
            'name' => 'Shop Owner',
            'email' => 'owner@family-bazar.com',
            'password' => bcrypt('password'),
        ]);

        // Create shop owner profile linked to the user
        \App\Models\Shop\ShopOwner::create([
            'user_id' => $shopOwnerUser->id,
            'nid' => '1234567890123',
            'status' => true,
        ]);

        $this->call([
            CategorySeeder::class,
            UnitSeeder::class,
            ProductSeeder::class,
            TagSeeder::class,
            TypeSeeder::class,
            BrandSeeder::class,
            DivisionSeeder::class,
            DistrictSeeder::class,
            UpazilaSeeder::class,
            UnionSeeder::class,
            ThanaSeeder::class,
            ZoneSeeder::class,
            RolesAndPermissionsSeeder::class,
        ]);
    }
}
