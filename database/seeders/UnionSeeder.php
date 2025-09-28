<?php

namespace Database\Seeders;

use App\Models\Union;
use App\Models\Upazila;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\File;

class UnionSeeder extends Seeder
{
    public function run(): void
    {
        // JSON ফাইল থেকে ডেটা পড়া
        $path = public_path('data/chittagong_upazila_unions_full.json');

        if (!File::exists($path)) {
            $this->command->error("❌ JSON ফাইল পাওয়া যায়নি: {$path}");
            return;
        }

        $json = File::get($path);
        $data = json_decode($json, true);

        if (!$data || !isset($data['upazilas'])) {
            $this->command->error("❌ JSON ফাইলের গঠন সঠিক নয়!");
            return;
        }

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
                            'name_bn' => null, // চাইলে পরে বাংলা নাম আপডেট করতে পারবেন
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
