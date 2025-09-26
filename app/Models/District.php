<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    protected $fillable = [
        'division_id',
        'name_en',
        'name_bn',
    ];

    public function division()
    {
        return $this->belongsTo(Division::class);
    }
}
