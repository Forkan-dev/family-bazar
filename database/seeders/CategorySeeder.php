<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product\Category;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $json = file_get_contents(database_path('data/grocery_categories.json'));
        $categories = json_decode($json, true);

        foreach ($categories as $categoryData) {
            $parentCategory = Category::create([
                'title_en' => $categoryData['en'],
                'title_bn' => $categoryData['bn'],
                'slug' => Str::slug($categoryData['en']),
            ]);

            $subcategories = [];
            foreach ($categoryData['subcategories'] as $subcategory) {
                $subcategories[] = [
                    'title_en' => $subcategory['en'],
                    'title_bn' => $subcategory['bn'],
                    'slug' => Str::slug($subcategory['en']),
                ];
            }

            $parentCategory->children()->createMany($subcategories);
        }
    }
}