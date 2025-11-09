<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TypeSeeder extends Seeder
{
    public function run()
    {
        $types = [
            [
                'id' => 1,
                'name_en' => 'Grocery',
                'name_bn' => 'মুদি',
                'settings' => json_encode(['isHome' => true, 'layoutType' => 'classic', 'productCard' => 'neon']),
                'slug' => 'grocery',
                'icon' => 'FruitsVegetable',
                'promotional_sliders' => json_encode([]),
                'created_at' => '2021-03-08 07:18:25',
                'updated_at' => '2021-09-26 15:23:32'
            ],
            [
                'id' => 2,
                'name_en' => 'Bakery',
                'name_bn' => 'বেকারি',
                'settings' => json_encode(['isHome' => false, 'layoutType' => 'standard', 'productCard' => 'argon']),
                'slug' => 'bakery',
                'icon' => 'Bakery',
                'promotional_sliders' => null,
                'created_at' => '2021-03-08 07:18:46',
                'updated_at' => '2021-08-18 13:34:28'
            ],
            [
                'id' => 3,
                'name_en' => 'Makeup',
                'name_bn' => 'মেকআপ',
                'settings' => json_encode(['isHome' => false, 'layoutType' => 'classic', 'productCard' => 'helium']),
                'slug' => 'makeup',
                'icon' => 'FacialCare',
                'promotional_sliders' => json_encode([]),
                'created_at' => '2021-03-08 07:19:12',
                'updated_at' => '2021-08-18 13:35:43'
            ],
            [
                'id' => 8,
                'name_en' => 'Books',
                'name_bn' => 'বই',
                'settings' => json_encode(['isHome' => false, 'layoutType' => 'compact', 'productCard' => 'radon']),
                'slug' => 'books',
                'icon' => 'BookIcon',
                'promotional_sliders' => json_encode([]),
                'created_at' => '2021-12-07 16:30:18',
                'updated_at' => '2021-12-08 13:06:56'
            ],
            [
                'id' => 5,
                'name_en' => 'Pharmacy',
                'name_bn' => 'ফার্মেসি',
                'settings' => json_encode(['isHome' => false, 'layoutType' => 'compact', 'productCard' => 'radon']),
                'slug' => 'pharmacy',
                'icon' => 'pharmacy',
                'promotional_sliders' => json_encode([]),
                'created_at' => '2021-12-07 16:30:18',
                'updated_at' => '2021-12-08 13:06:56'
            ]
        ];

        DB::table('types')->insert($types);
    }
}
