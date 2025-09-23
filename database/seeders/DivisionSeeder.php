<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DivisionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Division::create([
            'name_en' => 'Chittagong',
            'name_bn' => 'চট্টগ্রাম',
        ]);
    }
}
