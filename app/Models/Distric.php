<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Distric extends Model
{
    use HasFactory;

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
