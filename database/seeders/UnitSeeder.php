<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Unit;

class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $units = [
            ['name' => 'Kilogram', 'abbreviation' => 'kg'],
            ['name' => 'Gram', 'abbreviation' => 'g'],
            ['name' => 'Liter', 'abbreviation' => 'L'],
            ['name' => 'Milliliter', 'abbreviation' => 'ml'],
            ['name' => 'Piece', 'abbreviation' => 'pc'],
            ['name' => 'Dozen', 'abbreviation' => 'dz'],
            ['name' => 'Pound', 'abbreviation' => 'lb'],
            ['name' => 'Ounce', 'abbreviation' => 'oz'],
            ['name' => 'Gallon', 'abbreviation' => 'gal'],
            ['name' => 'Quart', 'abbreviation' => 'qt'],
            ['name' => 'Pint', 'abbreviation' => 'pt'],
            ['name' => 'Cup', 'abbreviation' => 'cup'],
            ['name' => 'Tablespoon', 'abbreviation' => 'tbsp'],
            ['name' => 'Teaspoon', 'abbreviation' => 'tsp'],
            ['name' => 'Meter', 'abbreviation' => 'm'],
            ['name' => 'Centimeter', 'abbreviation' => 'cm'],
            ['name' => 'Millimeter', 'abbreviation' => 'mm'],
            ['name' => 'Foot', 'abbreviation' => 'ft'],
            ['name' => 'Inch', 'abbreviation' => 'in'],
        ];

        foreach ($units as $unit) {
            Unit::create($unit);
        }
    }
}
