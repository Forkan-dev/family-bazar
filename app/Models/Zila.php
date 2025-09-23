<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Zila extends Model
{
    use HasFactory;

    protected $fillable = [
        'upazila_id',
        'name_en',
        'name_bn',
    ];

    public function upazila()
    {
        return $this->belongsTo(Upazila::class);
    }
}
