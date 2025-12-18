<?php

namespace App\Models\Product;

use App\Models\Document;
use App\Models\Unit;
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
        'sell_price',
        'quantity',
        'unit_id',
        'brand_id',
        'stock_quantity',
        'status',
        'category_id',
        'is_featured',
        'is_taxable',
        'is_cod_available',
        'is_refundable',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'is_taxable' => 'boolean',
        'is_cod_available' => 'boolean',
        'is_refundable' => 'boolean',
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
