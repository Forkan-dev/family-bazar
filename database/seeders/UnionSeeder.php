<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UnionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // This is an example for one upazila. You can follow the same pattern to add the rest of the unions.
        $bandarbanSadar = \App\Models\Upazila::where('name_en', 'Bandarban Sadar')->first();

        if ($bandarbanSadar) {
            $unions = [
                ['name_en' => 'Bandarban Sadar Union', 'name_bn' => 'বান্দরবান সদর ইউনিয়ন'],
                ['name_en' => 'Kuhalong Union', 'name_bn' => 'কুহালং ইউনিয়ন'],
                ['name_en' => 'Rajbila Union', 'name_bn' => 'রাজবিলা ইউনিয়ন'],
                ['name_en' => 'Suwalok Union', 'name_bn' => 'সুয়ালক ইউনিয়ন'],
                ['name_en' => 'Tangkabati Union', 'name_bn' => 'টংকাবতী ইউনিয়ন'],
            ];

            foreach ($unions as $union) {
                \App\Models\Union::create([
                    'upazila_id' => $bandarbanSadar->id,
                    'name_en' => $union['name_en'],
                    'name_bn' => $union['name_bn'],
                ]);
            }
        }
    }
}
