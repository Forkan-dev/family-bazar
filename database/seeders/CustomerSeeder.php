<?php

namespace Database\Seeders;

use App\Models\Customer\Customer;
use App\Models\User; // Import the Customer model
use Faker\Factory as Faker; // Import the User model
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        $users = User::all();

        foreach ($users as $user) {
            Customer::create([
                'user_id' => $user->id,
                'name' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'phone_number' => $faker->phoneNumber,
                'address' => $faker->address,
                'location' => json_encode([
                    'latitude' => $faker->latitude,
                    'longitude' => $faker->longitude,
                ]),
                'avatar_url' => $faker->imageUrl(),
            ]);
        }
    }
}
