<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CartItem extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'guest_id',
        'product_id',
        'quantity',
        'price',
        'options',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'options' => 'array',
        'price' => 'decimal:2',
    ];

    /**
     * Get the user that owns the cart item.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the product that owns the cart item.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Scope a query to only include items for a specific user or guest.
     */
    public function scopeForOwner($query, $userId = null, $guestId = null)
    {
        return $query->where(function ($q) use ($userId, $guestId) {
            if ($userId) {
                $q->where('user_id', $userId);
            } elseif ($guestId) {
                $q->where('guest_id', $guestId);
            }
        });
    }
}
