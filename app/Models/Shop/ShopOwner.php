<?php

namespace App\Models\Shop;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ShopOwner extends Model
{
    protected $fillable = [
        'user_id',
        'nid',
        'verified_at',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
        'verified_at' => 'datetime',
    ];

    /**
     * Get the user that owns the shop owner profile.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the shops for the shop owner.
     */
    public function shops(): HasMany
    {
        return $this->hasMany(Shop::class);
    }
}
