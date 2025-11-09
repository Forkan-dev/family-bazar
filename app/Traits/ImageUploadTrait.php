<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;

trait ImageUploadTrait
{

    protected function uploadMultipleImages($request, string $inputName, string $folder, $relation): void
    {
        if ($request->hasFile($inputName)) {
            foreach ($request->file($inputName) as $image) {
                $this->saveImageData($image, $folder, $relation);
            }
        }
    }


    protected function uploadSingleImage($request, string $inputName, string $folder, $relation): void
    {
        if ($request->hasFile($inputName)) {
            $image = $request->file($inputName);
            $this->saveImageData($image, $folder, $relation);
        }
    }

    private function saveImageData($image, string $folder, $relation): void
    {
        $path = $image->store($folder, 'public');

        $relation->create([
            'file_path' => $path,
            'file_name' => $image->getClientOriginalName(),
            'mime_type' => $image->getClientMimeType(),
            'file_size' => $image->getSize(),
        ]);
    }

    public function replaceSingleImage($request, string $inputName, string $folder, $relation, $oldImagePath = null): void
{
    if ($request->hasFile($inputName)) {
      
        if ($oldImagePath && Storage::disk('public')->exists($oldImagePath)) {
            Storage::disk('public')->delete($oldImagePath);
        }


        $image = $request->file($inputName);
        $this->saveImageData($image, $folder, $relation);
    }
}

}
