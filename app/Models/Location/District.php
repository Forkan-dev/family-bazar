<?php

namespace App\Models\Location;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class District extends Model
{
    protected $fillable = [
        'division_id',
        'name_en',
        'name_bn',
    ];

    /**
     * Get the division that owns the district.
     */
    public function division(): BelongsTo
    {
        return $this->belongsTo(Division::class);
    }

    /**
     * Get the upazilas for the district.
     */
    public function upazilas(): HasMany
    {
        return $this->hasMany(Upazila::class);
    }

    /**
     * Get the thanas for the district.
     */
    public function thanas(): HasMany
    {
        return $this->hasMany(Thana::class);
    }
}
