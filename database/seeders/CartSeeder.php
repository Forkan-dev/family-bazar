<?php

namespace Database\Seeders;

use App\Models\Cart;
use App\Models\User; // Import the Cart model
use Faker\Factory as Faker; // Import the User model
use Illuminate\Database\Seeder;

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
