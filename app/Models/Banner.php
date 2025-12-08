<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Banner extends Model
{
    use HasFactory;

    protected $fillable = [
        'type_id',
        'title',
        'description',
        'status',
        'language',
        'sub_title',
        'button_text_1',
        'position',
        'button_url_1',
        'button_text_2',
        'button_url_2',
    ];

    protected $casts = [
        'title' => 'array',
        'sub_title' => 'array',
        'description' => 'array',
    ];

    /**
     * Get the type that owns the Banner
     */
    public function type(): BelongsTo
    {
        return $this->belongsTo(Type::class);
    }

    public function documents()
    {
        return $this->morphMany(Document::class, 'documentable');
    }
}
