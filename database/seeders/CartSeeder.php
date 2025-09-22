<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Cart; // Import the Cart model
use App\Models\User; // Import the User model
use Faker\Factory as Faker;

class CartSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        $users = User::all();

        foreach ($users as $user) {
            Cart::create([
                'user_id' => $user->id,
                'status' => $faker->randomElement(['active', 'ordered', 'cancelled']),
                'total_price' => $faker->randomFloat(2, 10, 1000),
            ]);
        }
    }
}
