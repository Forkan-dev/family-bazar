<?php

namespace Database\Seeders;

use App\Models\Product\Brand;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Brand::create([
            'en_name' => 'Unilever',
            'bn_name' => 'ইউনিলিভার',
            'slug' => 'unilever',
            'image' => 'https://via.placeholder.com/150',
        ]);

        Brand::create([
            'en_name' => 'P&G',
            'bn_name' => 'পিএন্ডজি',
            'slug' => 'pg',
            'image' => 'https://via.placeholder.com/150',
        ]);

        Brand::create([
            'en_name' => 'Nestle',
            'bn_name' => 'নেসলে',
            'slug' => 'nestle',
            'image' => 'https://via.placeholder.com/150',
        ]);
    }
}
