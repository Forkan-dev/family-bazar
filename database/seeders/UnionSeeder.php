<?php

namespace Database\Seeders;

use App\Models\Union;
use App\Models\Upazila;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\File;

class UnionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
   {
        // JSON ফাইল থেকে ডেটা পড়া
        $jsonPath = database_path('data/chittagong_upazila_unions_full.json');
        $json = File::get($jsonPath);
        $data = json_decode($json, true);

        // upazilas লুপ চালানো
        foreach ($data['upazilas'] as $upazilaData) {
            $upazila = Upazila::where('name_en', $upazilaData['name'])->first();

            if ($upazila) {
                foreach ($upazilaData['unions'] as $unionName) {
                    Union::updateOrCreate(
                        [
                            'upazila_id' => $upazila->id,
                            'name_en' => $unionName,
                        ],
                        [
                            'name_bn' => null, // এখানে পরে বাংলা নাম দিলে আপডেট করতে পারবেন
                        ]
                    );
                }
            } else {
                $this->command->warn("⚠ Upazila পাওয়া যায়নি: {$upazilaData['name']}");
            }
        }

        $this->command->info("✅ Union ডেটা সফলভাবে seed করা হয়েছে!");
    }
}
