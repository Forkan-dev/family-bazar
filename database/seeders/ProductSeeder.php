<?php

namespace Database\Seeders;

use App\Models\Product\Category;
use App\Models\Product\Product;
use App\Models\Product\Unit;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $json = File::get(public_path('data/products.json'));
        $products = json_decode($json, true);

        foreach ($products as $productData) {
            $category = Category::where('title_en', $productData['category'])->first();
            if (!$category) {
                $this->command->warn("Skipping product '{$productData['name_en']}'. Category '{$productData['category']}' not found.");
                continue;
            }

            $unit = Unit::where('abbreviation', $productData['unit'])->first();
            if (!$unit) {
                $this->command->warn("Skipping product '{$productData['name_en']}'. Unit '{$productData['unit']}' not found.");
                continue;
            }

            Product::create([
                'name_en' => $productData['name_en'],
                'name_bn' => $productData['name_bn'] ?? null,
                'slug' => Str::slug($productData['name_en']),
                'category_id' => $category->id,
                'price' => $productData['price'],
                'quantity' => $productData['quantity'],
                'unit_id' => $unit->id,
                'description' => $productData['description_en'] ?? null,
                'stock_quantity' => 100, // Default stock
                'is_active' => true,
            ]);
        }
    }
}