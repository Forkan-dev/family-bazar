<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product\Unit; // Import the Unit model
use Faker\Factory as Faker;

class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        $units = [
            ['name' => 'Kilogram', 'abbreviation' => 'kg'],
            ['name' => 'Gram', 'abbreviation' => 'g'],
            ['name' => 'Liter', 'abbreviation' => 'L'],
            ['name' => 'Milliliter', 'abbreviation' => 'ml'],
            ['name' => 'Piece', 'abbreviation' => 'pcs'],
            ['name' => 'Dozen', 'abbreviation' => 'dz'],
            ['name' => 'Pack', 'abbreviation' => 'pk'],
        ];

        foreach ($units as $unit) {
            Unit::create($unit);
        }
    }
}
