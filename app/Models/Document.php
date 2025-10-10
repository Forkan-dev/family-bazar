<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Document extends Model
{
    use HasFactory;


    protected $fillable = [
        'documentable_id',
        'documentable_type',
        'file_path',
        'file_name',
        'mime_type',
        'file_size',
        'type',
        'alt_text',
        'status',
        'is_primary',
    ];

    protected $appends = ['url'];

    public function documentable()
    {
        return $this->morphTo();
    }

    public function getUrlAttribute()
    {
        return asset('storage/' . $this->file_path);
    }
}
