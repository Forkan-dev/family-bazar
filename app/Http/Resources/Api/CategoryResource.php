<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title_en' => $this->title_en,
            'title_bn' => $this->title_bn,
            'slug' => $this->slug,
            'description_en' => $this->description_en,
            'description_bn' => $this->description_bn,
            'icon' => $this->icon,
            'image' => $this->image,
            'parent_id' => $this->parent_id,
            'subcategories' => CategoryResource::collection($this->whenLoaded('children')),
        ];
    }
}
