<?php

namespace App\Models\Product;

use App\Models\Document;
use App\Models\Product\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'title_en',
        'title_bn',
        'slug',
        'description_en',
        'description_bn',
        'icon',
        'image',
        'parent_id',
    ];

    protected $appends = ['displayName'];


    public function documents()
    {
        return $this->morphMany(Document::class, 'documentable');
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function getDisplayNameAttribute()
    {
        return $this->title_en . ' (' . $this->title_bn . ')';
    }
}
