<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'abbreviation',
    ];

    protected $appends = ['displayName'];

    public function getDisplayNameAttribute()
    {
        return $this->name . ' (' . $this->abbreviation . ')';
    }
}
