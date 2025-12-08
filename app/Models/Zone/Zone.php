<?php

namespace App\Models\Zone;

use App\Models\Location\District;
use App\Models\Location\Thana;
use App\Models\Location\Upazila;
use App\Models\Order\Order;
use App\Models\Shop\Shop;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Zone extends Model
{
    protected $fillable = [
        'name',
        'description',
        'status',
        'address',
        'lat',
        'lon',
        'district_id',
        'upazila_id',
        'thana_id',
        'manager_id',
    ];

    protected $casts = [
        'status' => 'boolean',
        'lat' => 'decimal:7',
        'lon' => 'decimal:7',
    ];

    /**
     * Get the district for the zone.
     */
    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class);
    }

    /**
     * Get the upazila for the zone.
     */
    public function upazila(): BelongsTo
    {
        return $this->belongsTo(Upazila::class);
    }

    /**
     * Get the thana for the zone.
     */
    public function thana(): BelongsTo
    {
        return $this->belongsTo(Thana::class);
    }

    /**
     * Get the manager for the zone.
     */
    public function manager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    /**
     * Get the shops for the zone.
     */
    public function shops(): HasMany
    {
        return $this->hasMany(Shop::class);
    }

    /**
     * Get the orders for the zone.
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}
