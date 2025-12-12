<?php

namespace Database\Seeders;

use App\Models\Order\DeliveryMan;
use Faker\Factory as Faker; // Import the DeliveryMan model
use Illuminate\Database\Seeder;

class DeliveryManSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        for ($i = 0; $i < 5; $i++) { // Create 5 delivery men
            DeliveryMan::create([
                'name' => $faker->name,
                'phone_number' => $faker->phoneNumber,
                'email' => $faker->unique()->safeEmail,
                'avatar_url' => $faker->imageUrl(),
                'is_active' => $faker->boolean,
            ]);
        }
    }
}
