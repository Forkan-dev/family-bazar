<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Upazila extends Model
{
    protected $fillable = [
        'district_id',
        'name_en',
        'name_bn',
    ];

    public function district()
    {
        return $this->belongsTo(District::class);
    }
}
