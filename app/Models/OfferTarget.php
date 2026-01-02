<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class OfferTarget extends Model
{

    public const TYPE_PRODUCT = 'product';
    public const TYPE_CATEGORY = 'category';

    protected $fillable = [
        'id',
        'offer_id',
        'target_type',
        'target_id',
        'created_at',
        'updated_at'
    ];


    // Resolve actual target manually
    public function target()
    {
        return $this->morphTo();
    }

    public function offer()
    {
        return $this->belongsTo(Offer::class);
    }
}
