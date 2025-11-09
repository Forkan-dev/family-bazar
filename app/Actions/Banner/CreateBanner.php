<?php

namespace App\Actions\Banner;

use App\Models\Banner;
use App\Traits\ImageUploadTrait;
use Illuminate\Support\Facades\DB;
use App\Services\Contracts\ImageServiceInterface;
use App\Http\Requests\Admin\Banner\StoreBannerRequest;

class CreateBanner
{

    public function __construct(private ImageServiceInterface $imageService) {}
   public function handle(StoreBannerRequest $request): Banner
    {
        $validatedData = $request->validated();

        $processedData = [
            'title' => json_encode([
                'en' => $validatedData['title_en'],
                'bn' => $validatedData['title_bn']?? ''
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

        return DB::transaction(function () use ($processedData, $request) {
            $banner = Banner::create($processedData);

            // ✅ Single image upload call
            if ($request->hasFile('image')) {
                $this->imageService->uploadSingle(
                    $request->file('image'),
                    'banners',
                    $banner->documents()
                );
            }

            return $banner;
        });
    }


}
