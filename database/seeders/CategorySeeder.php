<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product\Category;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Category::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $json = file_get_contents(public_path('data/three_level_grocery.json'));

        $data = json_decode($json, true);

        $this->createCategory($data);
    }

    private function createCategory(array $categoryData, int $parentId = null): void
    {
        $category = Category::create([
            'title_en' => $categoryData['en'],
            'title_bn' => $categoryData['bn'],
            'slug' => Str::slug($categoryData['en']),
            'parent_id' => $parentId,
        ]);

        if (isset($categoryData['children'])) {
            foreach ($categoryData['children'] as $childData) {
                $this->createCategory($childData, $category->id);
            }
        }
    }
}
