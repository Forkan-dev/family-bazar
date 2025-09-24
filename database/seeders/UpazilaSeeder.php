<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UpazilaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $upazilas = [
            'Bandarban' => [
                ['name_en' => 'Bandarban Sadar', 'name_bn' => 'বান্দরবান সদর'],
                ['name_en' => 'Thanchi', 'name_bn' => 'থানচি'],
                ['name_en' => 'Lama', 'name_bn' => 'লামা'],
                ['name_en' => 'Naikhongchhari', 'name_bn' => 'নাইক্ষ্যংছড়ি'],
                ['name_en' => 'Ali Kadam', 'name_bn' => 'আলীকদম'],
                ['name_en' => 'Rowangchhari', 'name_bn' => 'রোয়াংছড়ি'],
                ['name_en' => 'Ruma', 'name_bn' => 'রুমা'],
            ],
            'Brahmanbaria' => [
                ['name_en' => 'Brahmanbaria Sadar', 'name_bn' => 'ব্রাহ্মণবাড়িয়া সদর'],
                ['name_en' => 'Ashuganj', 'name_bn' => 'আশুগঞ্জ'],
                ['name_en' => 'Nasirnagar', 'name_bn' => 'নাসিরনগর'],
                ['name_en' => 'Nabinagar', 'name_bn' => 'নবীনগর'],
                ['name_en' => 'Sarail', 'name_bn' => 'সরাইল'],
                ['name_en' => 'Shahbazpur Town', 'name_bn' => 'শাহবাজপুর শহর'],
                ['name_en' => 'Kasba', 'name_bn' => 'কসবা'],
                ['name_en' => 'Akhaura', 'name_bn' => 'আখাউড়া'],
                ['name_en' => 'Bancharampur', 'name_bn' => 'বাঞ্ছারামপুর'],
                ['name_en' => 'Bijoynagar', 'name_bn' => 'বিজয়নগর'],
            ],
            'Chandpur' => [
                ['name_en' => 'Chandpur Sadar', 'name_bn' => 'চাঁদপুর সদর'],
                ['name_en' => 'Faridganj', 'name_bn' => 'ফরিদগঞ্জ'],
                ['name_en' => 'Haimchar', 'name_bn' => 'হাইমচর'],
                ['name_en' => 'Haziganj', 'name_bn' => 'হাজীগঞ্জ'],
                ['name_en' => 'Kachua', 'name_bn' => 'কচুয়া'],
                ['name_en' => 'Matlab Dakshin', 'name_bn' => 'মতলব দক্ষিণ'],
                ['name_en' => 'Matlab Uttar', 'name_bn' => 'মতলব উত্তর'],
                ['name_en' => 'Shahrasti', 'name_bn' => 'শাহরাস্তি'],
            ],
            'Chattogram' => [
                ['name_en' => 'Anwara', 'name_bn' => 'আনোয়ারা'],
                ['name_en' => 'Banshkhali', 'name_bn' => 'বাঁশখালী'],
                ['name_en' => 'Boalkhali', 'name_bn' => 'বোয়ালখালী'],
                ['name_en' => 'Chandanaish', 'name_bn' => 'চন্দনাইশ'],
                ['name_en' => 'Fatikchhari', 'name_bn' => 'ফটিকছড়ি'],
                ['name_en' => 'Hathazari', 'name_bn' => 'হাটহাজারী'],
                ['name_en' => 'Lohagara', 'name_bn' => 'লোহাগাড়া'],
                ['name_en' => 'Mirsharai', 'name_bn' => 'মীরসরাই'],
                ['name_en' => 'Patiya', 'name_bn' => 'পটিয়া'],
                ['name_en' => 'Rangunia', 'name_bn' => 'রাঙ্গুনিয়া'],
                ['name_en' => 'Raozan', 'name_bn' => 'রাউজান'],
                ['name_en' => 'Sandwip', 'name_bn' => 'সন্দ্বীপ'],
                ['name_en' => 'Satkania', 'name_bn' => 'সাতকানিয়া'],
                ['name_en' => 'Sitakunda', 'name_bn' => 'সীতাকুণ্ড'],
            ],
            'Cox\'s Bazar' => [
                ['name_en' => 'Cox\'s Bazar Sadar', 'name_bn' => 'কক্সবাজার সদর'],
                ['name_en' => 'Chakaria', 'name_bn' => 'চকরিয়া'],
                ['name_en' => 'Kutubdia', 'name_bn' => 'কুতুবদিয়া'],
                ['name_en' => 'Maheshkhali', 'name_bn' => 'মহেশখালী'],
                ['name_en' => 'Ramu', 'name_bn' => 'রামু'],
                ['name_en' => 'Teknaf', 'name_bn' => 'টেকনাফ'],
                ['name_en' => 'Ukhia', 'name_bn' => 'উখিয়া'],
                ['name_en' => 'Pekua', 'name_bn' => 'পেকুয়া'],
            ],
            'Cumilla' => [
                ['name_en' => 'Barura', 'name_bn' => 'বরুড়া'],
                ['name_en' => 'Brahmanpara', 'name_bn' => 'ব্রাহ্মণপাড়া'],
                ['name_en' => 'Burichang', 'name_bn' => 'বুড়িচং'],
                ['name_en' => 'Chandina', 'name_bn' => 'চান্দিনা'],
                ['name_en' => 'Chauddagram', 'name_bn' => 'চৌদ্দগ্রাম'],
                ['name_en' => 'Daudkandi', 'name_bn' => 'দাউদকান্দি'],
                ['name_en' => 'Debidwar', 'name_bn' => 'দেবিদ্বার'],
                ['name_en' => 'Homna', 'name_bn' => 'হোমনা'],
                ['name_en' => 'Laksam', 'name_bn' => 'লাকসাম'],
                ['name_en' => 'Monohorgonj', 'name_bn' => 'মনোহরগঞ্জ'],
                ['name_en' => 'Meghna', 'name_bn' => 'মেঘনা'],
                ['name_en' => 'Muradnagar', 'name_bn' => 'মুরাদনগর'],
                ['name_en' => 'Nangalkot', 'name_bn' => 'নাঙ্গলকোট'],
                ['name_en' => 'Comilla Sadar', 'name_bn' => 'কুমিল্লা সদর'],
                ['name_en' => 'Sadar Dakshin', 'name_bn' => 'সদর দক্ষিণ'],
                ['name_en' => 'Titas', 'name_bn' => 'তিতাস'],
            ],
            'Feni' => [
                ['name_en' => 'Feni Sadar', 'name_bn' => 'ফেনী সদর'],
                ['name_en' => 'Chhagalnaiya', 'name_bn' => 'ছাগলনাইয়া'],
                ['name_en' => 'Daganbhuiyan', 'name_bn' => 'দাগনভূঞা'],
                ['name_en' => 'Parshuram', 'name_bn' => 'পরশুরাম'],
                ['name_en' => 'Fulgazi', 'name_bn' => 'ফুলগাজী'],
                ['name_en' => 'Sonagazi', 'name_bn' => 'সোনাগাজী'],
            ],
            'Khagrachhari' => [
                ['name_en' => 'Khagrachhari Sadar', 'name_bn' => 'খাগড়াছড়ি সদর'],
                ['name_en' => 'Dighinala', 'name_bn' => 'দীঘিনালা'],
                ['name_en' => 'Panchhari', 'name_bn' => 'পানছড়ি'],
                ['name_en' => 'Lakshmichhari', 'name_bn' => 'লক্ষীছড়ি'],
                ['name_en' => 'Mahalchhari', 'name_bn' => 'মহালছড়ি'],
                ['name_en' => 'Manikchhari', 'name_bn' => 'মানিকছড়ি'],
                ['name_en' => 'Ramgarh', 'name_bn' => 'রামগড়'],
                ['name_en' => 'Matiranga', 'name_bn' => 'মাটিরাঙ্গা'],
            ],
            'Lakshmipur' => [
                ['name_en' => 'Lakshmipur Sadar', 'name_bn' => 'লক্ষ্মীপুর সদর'],
                ['name_en' => 'Raipur', 'name_bn' => 'রায়পুর'],
                ['name_en' => 'Ramganj', 'name_bn' => 'রামগঞ্জ'],
                ['name_en' => 'Ramgati', 'name_bn' => 'রামগতি'],
                ['name_en' => 'Kamalnagar', 'name_bn' => 'কমলনগর'],
            ],
            'Noakhali' => [
                ['name_en' => 'Noakhali Sadar', 'name_bn' => 'নোয়াখালী সদর'],
                ['name_en' => 'Begumganj', 'name_bn' => 'বেগমগঞ্জ'],
                ['name_en' => 'Chatkhil', 'name_bn' => 'চাটখিল'],
                ['name_en' => 'Companiganj', 'name_bn' => 'কোম্পানীগঞ্জ'],
                ['name_en' => 'Hatiya', 'name_bn' => 'হাতিয়া'],
                ['name_en' => 'Senbagh', 'name_bn' => 'সেনবাগ'],
                ['name_en' => 'Sonaimuri', 'name_bn' => 'সোনাইমুড়ী'],
                ['name_en' => 'Subarnachar', 'name_bn' => 'সুবর্ণচর'],
            ],
            'Rangamati' => [
                ['name_en' => 'Rangamati Sadar', 'name_bn' => 'রাঙ্গামাটি সদর'],
                ['name_en' => 'Belaichhari', 'name_bn' => 'বেলাইছড়ি'],
                ['name_en' => 'Bagaichhari', 'name_bn' => 'বাঘাইছড়ি'],
                ['name_en' => 'Barkal', 'name_bn' => 'বরকল'],
                ['name_en' => 'Juraichhari', 'name_bn' => 'জুরাছড়ি'],
                ['name_en' => 'Rajasthali', 'name_bn' => 'রাজস্থলী'],
                ['name_en' => 'Kaptai', 'name_bn' => 'কাপ্তাই'],
                ['name_en' => 'Langadu', 'name_bn' => 'লংগদু'],
                ['name_en' => 'Naniarchar', 'name_bn' => 'নানিয়ারচর'],
                ['name_en' => 'Kaukhali', 'name_bn' => 'কাউখালী'],
            ],
        ];

        foreach ($upazilas as $districtName => $upazilaList) {
            $district = \App\Models\District::where('name_en', $districtName)->first();
            if ($district) {
                foreach ($upazilaList as $upazila) {
                    \App\Models\Upazila::create([
                        'district_id' => $district->id,
                        'name_en' => $upazila['name_en'],
                        'name_bn' => $upazila['name_bn'],
                    ]);
                }
            }
        }
    }
}