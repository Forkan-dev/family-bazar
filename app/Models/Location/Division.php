<?php

namespace App\Models\Location;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Division extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_en',
        'name_bn',
    ];

    /**
     * Get the districts for the division.
     */
    public function districts(): HasMany
    {
        return $this->hasMany(District::class);
    }
}
