<?php

namespace Database\Seeders;

use App\Models\Location\District;
use App\Models\Location\Thana;
use App\Models\Location\Upazila;
use App\Models\Zone\Zone;
use Illuminate\Database\Seeder;

class ZoneSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $district = District::where('name_en', 'Chattogram')->first();

        if (! $district) {
            $this->command->error('Chattogram district not found. Please run district seeder first.');

            return;
        }

        // Get thanas
        $panchlaish = Thana::where('name_en', 'Panchlaish')->where('district_id', $district->id)->first();
        $khulshi = Thana::where('name_en', 'Khulshi')->where('district_id', $district->id)->first();
        $kotwali = Thana::where('name_en', 'Kotwali')->where('district_id', $district->id)->first();
        $halishahar = Thana::where('name_en', 'Halishahar')->where('district_id', $district->id)->first();
        $bayazid = Thana::where('name_en', 'Bayazid Bostami')->where('district_id', $district->id)->first();
        $doubleMooring = Thana::where('name_en', 'Double Mooring')->where('district_id', $district->id)->first();
        $chandgaon = Thana::where('name_en', 'Chandgaon')->where('district_id', $district->id)->first();
        $bandar = Thana::where('name_en', 'Bandar')->where('district_id', $district->id)->first();

        // Get upazilas
        $patiya = Upazila::where('name_en', 'Patiya')->where('district_id', $district->id)->first();
        $sitakunda = Upazila::where('name_en', 'Sitakunda')->where('district_id', $district->id)->first();
        $anwara = Upazila::where('name_en', 'Anwara')->where('district_id', $district->id)->first();
        $hathazari = Upazila::where('name_en', 'Hathazari')->where('district_id', $district->id)->first();
        $rangunia = Upazila::where('name_en', 'Rangunia')->where('district_id', $district->id)->first();
        $mirsharai = Upazila::where('name_en', 'Mirsharai')->where('district_id', $district->id)->first();

        $zones = [
            // City zones with thanas
            [
                'name' => 'Chattogram City - Panchlaish Zone',
                'description' => 'Covers Panchlaish thana area including residential and commercial zones',
                'district_id' => $district->id,
                'thana_id' => $panchlaish?->id,
                'address' => 'Panchlaish, Chattogram',
                'lat' => 22.3569,
                'lon' => 91.7832,
                'status' => true,
            ],
            [
                'name' => 'Chattogram City - Khulshi Zone',
                'description' => 'Covers Khulshi thana area including hills and residential areas',
                'district_id' => $district->id,
                'thana_id' => $khulshi?->id,
                'address' => 'Khulshi, Chattogram',
                'lat' => 22.3450,
                'lon' => 91.8050,
                'status' => true,
            ],
            [
                'name' => 'Chattogram City - Kotwali Zone',
                'description' => 'Covers Kotwali thana area including old town and commercial center',
                'district_id' => $district->id,
                'thana_id' => $kotwali?->id,
                'address' => 'Kotwali, Chattogram',
                'lat' => 22.3384,
                'lon' => 91.8317,
                'status' => true,
            ],
            [
                'name' => 'Chattogram City - Halishahar Zone',
                'description' => 'Covers Halishahar thana area including port and industrial zones',
                'district_id' => $district->id,
                'thana_id' => $halishahar?->id,
                'address' => 'Halishahar, Chattogram',
                'lat' => 22.2938,
                'lon' => 91.8074,
                'status' => true,
            ],
            [
                'name' => 'Chattogram City - Bayazid Zone',
                'description' => 'Covers Bayazid Bostami thana area',
                'district_id' => $district->id,
                'thana_id' => $bayazid?->id,
                'address' => 'Bayazid, Chattogram',
                'lat' => 22.3150,
                'lon' => 91.8467,
                'status' => true,
            ],
            [
                'name' => 'Chattogram City - Double Mooring Zone',
                'description' => 'Covers Double Mooring thana area',
                'district_id' => $district->id,
                'thana_id' => $doubleMooring?->id,
                'address' => 'Double Mooring, Chattogram',
                'lat' => 22.3475,
                'lon' => 91.8123,
                'status' => true,
            ],
            [
                'name' => 'Chattogram City - Chandgaon Zone',
                'description' => 'Covers Chandgaon thana area',
                'district_id' => $district->id,
                'thana_id' => $chandgaon?->id,
                'address' => 'Chandgaon, Chattogram',
                'lat' => 22.3280,
                'lon' => 91.8200,
                'status' => true,
            ],
            [
                'name' => 'Chattogram City - Bandar Zone',
                'description' => 'Covers Bandar thana area including port facilities',
                'district_id' => $district->id,
                'thana_id' => $bandar?->id,
                'address' => 'Bandar, Chattogram',
                'lat' => 22.3100,
                'lon' => 91.8000,
                'status' => true,
            ],

            // Upazila zones
            [
                'name' => 'Chattogram - Patiya Zone',
                'description' => 'Covers Patiya upazila area',
                'district_id' => $district->id,
                'upazila_id' => $patiya?->id,
                'address' => 'Patiya, Chattogram',
                'lat' => 22.1553,
                'lon' => 91.9790,
                'status' => true,
            ],
            [
                'name' => 'Chattogram - Sitakunda Zone',
                'description' => 'Covers Sitakunda upazila area',
                'district_id' => $district->id,
                'upazila_id' => $sitakunda?->id,
                'address' => 'Sitakunda, Chattogram',
                'lat' => 22.6302,
                'lon' => 91.6641,
                'status' => true,
            ],
            [
                'name' => 'Chattogram - Anwara Zone',
                'description' => 'Covers Anwara upazila area',
                'district_id' => $district->id,
                'upazila_id' => $anwara?->id,
                'address' => 'Anwara, Chattogram',
                'lat' => 22.1640,
                'lon' => 91.7480,
                'status' => true,
            ],
            [
                'name' => 'Chattogram - Hathazari Zone',
                'description' => 'Covers Hathazari upazila area',
                'district_id' => $district->id,
                'upazila_id' => $hathazari?->id,
                'address' => 'Hathazari, Chattogram',
                'lat' => 22.4833,
                'lon' => 91.8000,
                'status' => true,
            ],
            [
                'name' => 'Chattogram - Rangunia Zone',
                'description' => 'Covers Rangunia upazila area',
                'district_id' => $district->id,
                'upazila_id' => $rangunia?->id,
                'address' => 'Rangunia, Chattogram',
                'lat' => 22.5333,
                'lon' => 91.9000,
                'status' => true,
            ],
            [
                'name' => 'Chattogram - Mirsharai Zone',
                'description' => 'Covers Mirsharai upazila area',
                'district_id' => $district->id,
                'upazila_id' => $mirsharai?->id,
                'address' => 'Mirsharai, Chattogram',
                'lat' => 22.7500,
                'lon' => 91.6167,
                'status' => true,
            ],
        ];

        foreach ($zones as $zone) {
            Zone::create($zone);
        }

        $this->command->info('Zone seeder completed successfully!');
    }
}
