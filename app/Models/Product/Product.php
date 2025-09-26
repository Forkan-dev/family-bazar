<?php

namespace App\Models\Product;

use App\Models\Document;
use App\Models\Product\Brand; // Import the Brand model
use App\Models\Product\Category;
use App\Models\Product\Tag;
use App\Models\Product\Unit; // Import the Unit model
use App\Models\Product\Review; // Import the Review model
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_en',
        'name_bn',
        'slug',
        'description',
        'price',
        'quantity',
        'unit_id',
        'brand_id', // Add this line
        'stock_quantity',
        'is_active',
        'category_id',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function documents()
    {
        return $this->morphMany(Document::class, 'documentable');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function brand(): BelongsTo // Add this method
    {
        return $this->belongsTo(Brand::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }
}
