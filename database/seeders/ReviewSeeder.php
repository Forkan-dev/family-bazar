<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product\Review; // Import the Review model
use App\Models\User; // Import the User model
use App\Models\Product\Product; // Import the Product model
use Faker\Factory as Faker;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        $users = User::all();
        $products = Product::all();

        if ($users->isEmpty() || $products->isEmpty()) {
            // Handle case where no users or products exist to link reviews to
            return;
        }

        foreach ($users as $user) {
            // Create a few reviews for each user
            for ($i = 0; $i < $faker->numberBetween(1, 3); $i++) {
                Review::create([
                    'user_id' => $user->id,
                    'product_id' => $faker->randomElement($products->pluck('id')->toArray()),
                    'rating' => $faker->numberBetween(1, 5),
                    'comment' => $faker->optional()->sentence,
                ]);
            }
        }
    }
}
