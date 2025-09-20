<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product\Tag;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Tag::create([
            'name' => 'Organic',
            'slug' => 'organic',
        ]);

        Tag::create([
            'name' => 'Fresh',
            'slug' => 'fresh',
        ]);

        Tag::create([
            'name' => 'Frozen',
            'slug' => 'frozen',
        ]);

        Tag::create([
            'name' => 'Sale',
            'slug' => 'sale',
        ]);
    }
}
