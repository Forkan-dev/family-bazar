<?php

namespace Database\Seeders;

use App\Models\Location\Division;
use Illuminate\Database\Seeder;

class DivisionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Division::create([
            'name_en' => 'Chittagong',
            'name_bn' => 'চট্টগ্রাম',
        ]);
    }
}
