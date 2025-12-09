<?php

namespace App\Models;

use App\Models\Customer\Customer;
use App\Models\Product;
use App\Models\Order\Order;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CartHistory extends Model
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
        'action',
        'order_id',
        'action_date',
        'expires_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'options' => 'array',
        'price' => 'decimal:2',
        'action_date' => 'datetime',
        'expires_at' => 'datetime',
    ];

    /**
     * Get the user that owns the cart history.
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get the product that owns the cart history.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the order that owns the cart history.
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Scope a query to only include history for a specific user or guest.
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


    public function scopeExpired(Builder $query): Builder
    {
        return $query->where('expires_at', '<=', now());
    }
}
