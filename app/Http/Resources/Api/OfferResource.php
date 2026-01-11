<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OfferResource extends JsonResource
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
            'name' => $this->name,
            'name_bn' => $this->name_bn,
            'discount_type' => $this->discount_type,
            'discount_value' => $this->value,
            'start_date' => datetime_parse_utc_to_local($this->start_at, 'Y-m-d\TH:i'),
            'end_date' => datetime_parse_utc_to_local($this->end_at, 'Y-m-d\TH:i'),
            'is_active' => $this->is_active,
            'max_discount_amount' => $this->max_discount_amount,
        ];
    }
}
