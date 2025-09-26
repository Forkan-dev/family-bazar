<?php

namespace App\Models\Product;

use App\Models\Document;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use HasFactory;

    protected $fillable = ['en_name', 'bn_name', 'slug', 'image'];

    public function documents()
    {
        return $this->morphMany(Document::class, 'documentable');
    }

    // Accessor for image_url
    public function getImageUrlAttribute()
    {
        return $this->image ? asset($this->image) : null;
    }
}
