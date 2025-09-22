<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product\Product; // Import the Product model
use App\Models\Product\Category; // Import the Category model
use App\Models\Product\Unit; // Import the Unit model
use Faker\Factory as Faker;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        $categories = Category::all();
        $units = Unit::all();

        if ($categories->isEmpty() || $units->isEmpty()) {
            // Handle case where no categories or units exist to link products to
            return;
        }

        for ($i = 0; $i < 20; $i++) { // Create 20 products
            $name_en = $faker->words(3, true);
            Product::create([
                'name_en' => $name_en,
                'name_bn' => 'বাংলা ' . $name_en, // Example Bengali title
                'slug' => Str::slug($name_en),
                'category_id' => $faker->randomElement($categories->pluck('id')->toArray()),
                'price' => $faker->randomFloat(2, 10, 500),
                'quantity' => $faker->randomFloat(2, 0.5, 10),
                'unit_id' => $faker->randomElement($units->pluck('id')->toArray()),
                'description' => $faker->paragraph,
                'image_url' => $faker->imageUrl(),
                'stock_quantity' => $faker->numberBetween(0, 100),
                'is_active' => $faker->boolean,
            ]);
        }
    }
}
