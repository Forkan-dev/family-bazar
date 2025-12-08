<?php

namespace App\Models\Shop;

use App\Models\Zone\Zone;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Shop extends Model
{
    protected $fillable = [
        'shop_owner_id',
        'name',
        'commission_rate',
        'zone_id',
        'lat',
        'lon',
        'type',
        'is_commission_based',
        'status',
    ];

    protected $casts = [
        'commission_rate' => 'decimal:2',
        'lat' => 'decimal:7',
        'lon' => 'decimal:7',
        'is_commission_based' => 'boolean',
        'status' => 'boolean',
    ];

    /**
     * Get the shop owner that owns the shop.
     */
    public function shopOwner(): BelongsTo
    {
        return $this->belongsTo(ShopOwner::class);
    }

    /**
     * Get the zone that the shop belongs to.
     */
    public function zone(): BelongsTo
    {
        return $this->belongsTo(Zone::class);
    }
}
