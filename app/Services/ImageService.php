<?php

namespace App\Services;

use App\Services\Contracts\ImageServiceInterface;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Image;

class ImageService implements ImageServiceInterface
{
    public function uploadSingle($file, string $folder, $relation): void
    {
        $this->saveImage($file, $folder, $relation);
    }

    public function uploadMultiple(array $files, string $folder, $relation): void
    {
        foreach ($files as $file) {
            $this->saveImage($file, $folder, $relation);
        }
    }

    public function replaceSingle($file, string $folder, $relation, ?string $oldFilePath = null): void
    {
        if ($oldFilePath && Storage::disk('public')->exists($oldFilePath)) {
            Storage::disk('public')->delete($oldFilePath);
        }

        $this->saveImage($file, $folder, $relation);
    }

    public function resize($file, int $width, int $height): string
    {
        $image = Image::make($file)->resize($width, $height, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });

        $filename = time().'_'.uniqid().'.'.$file->getClientOriginalExtension();
        $path = "resized/{$filename}";

        Storage::disk('public')->put($path, (string) $image->encode());

        return $path;
    }

    private function saveImage($file, string $folder, $relation): void
    {
        $path = $file->store($folder, 'public');

        $relation->create([
            'file_path' => $path,
            'file_name' => $file->getClientOriginalName(),
            'mime_type' => $file->getClientMimeType(),
            'file_size' => $file->getSize(),
        ]);
    }
}
