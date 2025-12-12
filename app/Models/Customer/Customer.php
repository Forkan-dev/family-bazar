<?php

namespace App\Models\Customer;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class Customer extends Authenticatable
{
    use HasApiTokens;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'email',
        'password',
        'phone_number',
        'address',
        'location',
        'avatar_url',
        'ip_address',
        'mac_address',
        'otp_code',
        'otp_expires_at',
        'otp_attempts',
        'last_otp_sent_at',
        'is_phone_verified',



    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'location' => 'array',
    ];

    /**
     * Get the user that owns the customer.
     */

}
