<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Order\DeliveryMan; // Import the DeliveryMan model
use Faker\Factory as Faker;

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
