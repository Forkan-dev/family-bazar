<?php

namespace App\Models\Location;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Upazila extends Model
{
    protected $fillable = [
        'district_id',
        'name_en',
        'name_bn',
    ];

    /**
     * Get the district that owns the upazila.
     */
    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class);
    }
}
