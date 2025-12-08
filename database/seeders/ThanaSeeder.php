<?php

namespace Database\Seeders;

use App\Models\Location\Thana;
use Illuminate\Database\Seeder;

class ThanaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $thanas = [
            ['name_en' => 'Akbar Shah', 'name_bn' => 'আকবর শাহ', 'district_id' => 4],
            ['name_en' => 'Bakalia', 'name_bn' => 'বাকলিয়া', 'district_id' => 4],
            ['name_en' => 'Bandar', 'name_bn' => 'বন্দর', 'district_id' => 4],
            ['name_en' => 'Bayazid Bostami', 'name_bn' => 'বায়েজিদ বোস্তামী', 'district_id' => 4],
            ['name_en' => 'Chandgaon', 'name_bn' => 'চান্দগাঁও', 'district_id' => 4],
            ['name_en' => 'Chawkbazar', 'name_bn' => 'চকবাজার', 'district_id' => 4],
            ['name_en' => 'Double Mooring', 'name_bn' => 'ডাবল মুরিং', 'district_id' => 4],
            ['name_en' => 'EPZ', 'name_bn' => 'ইপিজেড', 'district_id' => 4],
            ['name_en' => 'Halishahar', 'name_bn' => 'হালিশহর', 'district_id' => 4],
            ['name_en' => 'Khulshi', 'name_bn' => 'খুলশী', 'district_id' => 4],
            ['name_en' => 'Kotwali', 'name_bn' => 'কোতোয়ালী', 'district_id' => 4],
            ['name_en' => 'Pahartali', 'name_bn' => 'পাহাড়তলী', 'district_id' => 4],
            ['name_en' => 'Panchlaish', 'name_bn' => 'পাঁচলাইশ', 'district_id' => 4],
            ['name_en' => 'Patenga', 'name_bn' => 'পতেঙ্গা', 'district_id' => 4],
            ['name_en' => 'Sadarghat', 'name_bn' => 'সদরঘাট', 'district_id' => 4],
        ];

        foreach ($thanas as $thana) {
            Thana::create($thana);
        }
    }
}
