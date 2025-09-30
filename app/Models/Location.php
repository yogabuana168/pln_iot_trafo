<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'address', 'latitude', 'longitude', 'city', 'province', 'meta'
    ];

    protected $casts = [
        'meta' => 'array',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    public function transformers()
    {
        return $this->hasMany(Transformer::class);
    }
}
