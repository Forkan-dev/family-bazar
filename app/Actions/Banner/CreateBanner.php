<?php

namespace App\Actions\Banner;

use App\Models\Banner;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\Admin\Banner\StoreBannerRequest;
use App\Traits\ImageUploadTrait;

class CreateBanner
{
    use ImageUploadTrait;
    public function handle(StoreBannerRequest $request): Banner
    {
        $validatedData = $request->validated();

        $processedData = [
            'title' => json_encode([
                'en' => $validatedData['title_en'],
                'bn' => $validatedData['title_bn'] ?? ''
            ]),
            'sub_title' => json_encode([
                'en' => $validatedData['sub_title_en'] ?? '',
                'bn' => $validatedData['sub_title_bn'] ?? ''
            ]),
            'description' => json_encode([
                'en' => $validatedData['description_en'] ?? '',
                'bn' => $validatedData['description_bn'] ?? ''
            ]),
            'position' => $validatedData['position'],
            'status' => $validatedData['status'] ?? 'inactive',
            'button_text_1' => $validatedData['button_text_1'],
            'button_url_1' => $validatedData['button_url_1'],
            'button_text_2' => $validatedData['button_text_2'],
            'button_url_2' => $validatedData['button_url_2'],
            'type_id' => $validatedData['type_id'],
        ];


        $banner = Banner::create($processedData);

        // ✅ Single image upload call
        if ($request->hasFile('image')) {
            $this->uploadSingleImage($request, 'image', 'banners', $banner->documents());
        }

        return $banner;
    }
}
