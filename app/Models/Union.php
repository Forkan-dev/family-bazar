<?php

namespace App\Models;

use App\Models\Location\Upazila;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Union extends Model
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
