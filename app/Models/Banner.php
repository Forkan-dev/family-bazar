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
        'image',
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
        'image' => 'array',
    ];

    /**
     * Get the type that owns the Banner
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function type(): BelongsTo
    {
        return $this->belongsTo(Type::class);
    }
}
