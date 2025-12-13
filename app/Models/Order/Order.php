<?php

namespace App\Models\Order;

use App\Models\Cart;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany; // Import the User model
use Illuminate\Database\Eloquent\Relations\HasOne; // Import the Cart model

class Order extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'customer_id',
        'customer_address_id',
        'delivery_address',
        'zone_id',
        'total_amount',
        'discount_amount',
        'discount_type',
        'discount_value',
        'offer_id',
        'applied_offer',
        'payment_method',
        'payment_status',
        'status',
        'notes',
        'total_commission_amount',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'delivery_address' => 'array',
        'applied_offer' => 'array',
        'total_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total_commission_amount' => 'decimal:2',
    ];

    /**
     * Get the user that owns the order.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the cart that belongs to the order.
     */
    public function cart(): BelongsTo
    {
        return $this->belongsTo(Cart::class);
    }

    /**
     * Get the order items for the order.
     */
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Get the payment associated with the order.
     */
    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }

    /**
     * Get the delivery associated with the order.
     */
    public function delivery(): HasOne
    {
        return $this->hasOne(Delivery::class);
    }
}
