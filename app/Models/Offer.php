<?php

namespace App\Models;

use App\Models\Product\Category;
use App\Models\Product\Product;
use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    public const TYPE_PRODUCT = 'product';
    public const TYPE_CATEGORY = 'category';

   public function offerTargets()
    {
        return $this->hasMany(OfferTarget::class);
    }

    // Optional helpers
   public function products()
    {
        return $this->morphedByMany(
            Product::class,
            'target',
            'offer_targets'
        );
    }

    public function categories()
    {
        return $this->morphedByMany(
            Category::class,
            'target',
            'offer_targets'
        );
    }
}
