<?php

namespace App\Models\Product;

use App\Models\Product\Category;
use App\Models\Product\Tag;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'title_en',
        'title_bn',
        'slug',
        'description',
        'price',
        'stock',
        'image_url',
        'category_id',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }
}
