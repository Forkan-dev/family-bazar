<?php

namespace App\Models\Customer;

use App\Models\User;
use App\Models\Zone\Zone;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomerAddress extends Model
{
    protected $fillable = [
        'address_line',
        'customer_id',
        'zone_id',
        'state',
        'postal_code',
        'phone',
        'division_id',
        'district_id',
        'upazila_id',
        'union_id',
        'thana_id',
    ];

    /**
     * Get the customer that owns the address.
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    /**
     * Get the zone for the address.
     */
    public function zone(): BelongsTo
    {
        return $this->belongsTo(Zone::class);
    }
}
