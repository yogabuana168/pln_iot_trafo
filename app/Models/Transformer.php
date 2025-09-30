<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transformer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'code', 'location_id', 'transformer_type', 'capacity_kva', 
        'voltage_primary', 'voltage_secondary', 'manufacturer', 'year_installed', 
        'is_active', 'maintenance_schedule', 'notes'
    ];

    protected $casts = [
        'capacity_kva' => 'decimal:2',
        'voltage_primary' => 'decimal:2',
        'voltage_secondary' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function location()
    {
        return $this->belongsTo(Location::class);
    }
}


