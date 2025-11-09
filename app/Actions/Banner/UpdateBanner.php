<?php

namespace App\Actions\Banner;

use App\Models\Banner;
use App\Traits\ImageUploadTrait;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\Admin\Banner\UpdateBannerRequest;

class UpdateBanner
{
    use ImageUploadTrait;

    public function handle(UpdateBannerRequest $request, Banner $banner): Banner
    {
        $validatedData = $request->validated();
        
        $processedData = [
            'title' => json_encode([
                'en' => $validatedData['title_en'],
                'bn' => $validatedData['title_bn']
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

        return DB::transaction(function () use ($processedData, $request, $banner) {
            $banner->update($processedData);

            // Load the documents relationship to get the old image path
            $banner->load('documents');
            $oldImagePath = $banner->documents->first() ? $banner->documents->first()->file_path : null;

            $this->replaceSingleImage(
                $request,
                'image',              // input name
                'banners',            // folder name
                $banner->documents(), // relation
                $oldImagePath        // old image path
            );

            return $banner;
        });
    }
}
