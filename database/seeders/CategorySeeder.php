<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $grocery = Category::create([
            'title_en' => 'Grocery',
            'title_bn' => 'মুদি',
            'slug' => 'grocery',
        ]);

        $grocery->children()->createMany([
            [
                'title_en' => 'Spice (Moshla)',
                'title_bn' => 'মসলা',
                'slug' => 'spice-moshla',
            ],
            [
                'title_en' => 'Noodles',
                'title_bn' => 'নুডলস',
                'slug' => 'noodles',
            ],
            [
                'title_en' => 'Pasta',
                'title_bn' => 'পাস্তা',
                'slug' => 'pasta',
            ],
            [
                'title_en' => 'Dairy',
                'title_bn' => 'দুগ্ধ',
                'slug' => 'dairy',
            ],
            [
                'title_en' => 'Vegetable',
                'title_bn' => 'সবজি',
                'slug' => 'vegetable',
            ],
            [
                'title_en' => 'Bakery',
                'title_bn' => 'বেকারি',
                'slug' => 'bakery',
            ],
        ]);
    }
}