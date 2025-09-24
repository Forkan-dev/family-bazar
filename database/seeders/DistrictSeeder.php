<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DistrictSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $chittagong = \App\Models\Division::where('name_en', 'Chittagong')->first();

        $districts = [
            ['name_en' => 'Bandarban', 'name_bn' => 'বান্দরবান'],
            ['name_en' => 'Brahmanbaria', 'name_bn' => 'ব্রাহ্মণবাড়িয়া'],
            ['name_en' => 'Chandpur', 'name_bn' => 'চাঁদপুর'],
            ['name_en' => 'Chattogram', 'name_bn' => 'চট্টগ্রাম'],
            ['name_en' => 'Cox\'s Bazar', 'name_bn' => 'কক্সবাজার'],
            ['name_en' => 'Cumilla', 'name_bn' => 'কুমিল্লা'],
            ['name_en' => 'Feni', 'name_bn' => 'ফেনী'],
            ['name_en' => 'Khagrachhari', 'name_bn' => 'খাগড়াছড়ি'],
            ['name_en' => 'Lakshmipur', 'name_bn' => 'লক্ষ্মীপুর'],
            ['name_en' => 'Noakhali', 'name_bn' => 'নোয়াখালী'],
            ['name_en' => 'Rangamati', 'name_bn' => 'রাঙ্গামাটি'],
        ];

        foreach ($districts as $district) {
            \App\Models\District::create([
                'division_id' => $chittagong->id,
                'name_en' => $district['name_en'],
                'name_bn' => $district['name_bn'],
            ]);
        }
    }
}
