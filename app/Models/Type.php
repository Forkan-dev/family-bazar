<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'language',
        'slug',
        'icon',
        'promotional_sliders',
        'settings',
    ];

    protected $casts = [
        'promotional_sliders' => 'array',
        'settings' => 'array',
    ];
}
