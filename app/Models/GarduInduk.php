<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GarduInduk extends Model
{
    protected $table = 'gardu_induk';
    
    protected $fillable = [
        'kode_gi',
        'nama_gi',
        'alamat',
        'koordinat_lat',
        'koordinat_long',
        'kabupaten',
        'provinsi',
        'tegangan_level_in_kv',
        'tegangan_level_out_kv',
        'jumlah_bay',
        'kapasitas_mva',
        'tahun_operasi',
        'status',
        'keterangan'
    ];

    protected $casts = [
        'koordinat_lat' => 'decimal:6',
        'koordinat_long' => 'decimal:6',
        'tegangan_level_in_kv' => 'decimal:2',
        'tegangan_level_out_kv' => 'decimal:2',
        'kapasitas_mva' => 'decimal:2',
        'jumlah_bay' => 'integer',
        'tahun_operasi' => 'integer'
    ];
}
