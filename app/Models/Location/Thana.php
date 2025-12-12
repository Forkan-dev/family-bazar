<?php

namespace App\Models\Location;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Thana extends Model
{
    protected $fillable = [
        'district_id',
        'name_en',
        'name_bn',
    ];

    /**
     * Get the district that owns the thana.
     */
    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class);
    }
}
