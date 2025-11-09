<?php

namespace App\Services\Contracts;

interface ImageServiceInterface
{
    public function uploadSingle($file, string $folder, $relation): void;
    public function uploadMultiple(array $files, string $folder, $relation): void;
    public function replaceSingle($file, string $folder, $relation, ?string $oldFilePath = null): void;
    public function resize($file, int $width, int $height): string;
}
