<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SidebarResource extends JsonResource
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
            'name' => $this->title_en,
            'name_bn' => $this->title_bn,
            'slug' => $this->slug,
            'icon' => $this->icon,
            'image' => $this->image,
            'parent_id' => $this->parent_id,
            'subcategories' => SidebarResource::collection($this->whenLoaded('children')),
        ];
    }
}
