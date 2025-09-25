<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use HasFactory;

    protected $fillable = ['en_name', 'bn_name', 'slug', 'image'];

    // Accessor for image_url
    public function getImageUrlAttribute()
    {
        return $this->image ? asset($this->image) : null;
    }
}
