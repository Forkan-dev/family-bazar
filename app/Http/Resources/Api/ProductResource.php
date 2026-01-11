<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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
            'name' => $this->name_en,
            'name_bn' => $this->name_bn,
            'description' => $this->description,
            'price' => (int) $this->price,
            'sale_price' => $this->sale_price,
            'stock' => $this->stock_quantity,
            'image_url' => $this->image_url,
            'has_offer' => $this->hasOffer(),
            'sale_price' => $this->getPriceAfterDscount(),
            'offer_name' => $this->hasOffer() && $this->offers->isNotEmpty() ? $this->offers->first()->name : null,
            'discounted_amount' => $this->getDiscountedAmountAttribute(),
            'category' => new CategoryResource($this->whenLoaded('category')),
            'tags' => TagResource::collection($this->whenLoaded('tags')),
            'offers' => OfferResource::collection($this->whenLoaded('offers')),
            'unit' => new UnitResource($this->whenLoaded('unit')),

        ];
    }

    public function getPriceAfterDscount()
    {
        $finalPrice = $this->price;

        if ($this->offers && $this->offers->isNotEmpty()) {
            foreach ($this->offers as $offer) {
                if ($offer->discount_type === 'percentage') {
                    $discountAmount = ($offer->value / 100) * $finalPrice;
                    if ($offer->max_discount_amount && $discountAmount > $offer->max_discount_amount) {
                        $discountAmount = $offer->max_discount_amount;
                    }
                    $finalPrice -= $discountAmount;
                } elseif ($offer->discount_type === 'fixed') {
                    $maxDiscount = $offer->max_discount_amount;
                    if ($offer->value > $maxDiscount) {
                        $finalPrice -= $maxDiscount;
                        continue;;
                    }
                    $finalPrice -= $offer->value;
                }
            }
        }

        return $finalPrice;
    }
    public function hasOffer()
    {
        $currentTimeUTC = now(config('app.timezone'))->setTimezone('UTC');
        return $this->offers && $this->offers->isNotEmpty() && $this->offers->first()->start_at <= $currentTimeUTC && $this->offers->first()->end_at >= $currentTimeUTC && $this->offers->first()->is_active;
    }

    public function getDiscountedAmountAttribute()
    {
        return $this->price - $this->getPriceAfterDscount();
    }
}
