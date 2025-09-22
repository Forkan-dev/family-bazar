<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Order\Order; // Import the Order model
use App\Models\User; // Import the User model
use App\Models\Cart; // Import the Cart model
use Faker\Factory as Faker;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        $users = User::all();
        $carts = Cart::all();

        foreach ($users as $user) {
            // Create an order for each user, linking to a random cart
            Order::create([
                'user_id' => $user->id,
                'cart_id' => $faker->randomElement($carts->pluck('id')->toArray()),
                'total_price' => $faker->randomFloat(2, 50, 2000),
                'status' => $faker->randomElement(['pending', 'in-progress', 'delivered', 'cancelled']),
                'delivery_address' => $faker->address,
                'expected_delivery_time' => $faker->dateTimeBetween('now', '+1 week'),
                'payment_status' => $faker->randomElement(['pending', 'completed', 'failed']),
            ]);
        }
    }
}
