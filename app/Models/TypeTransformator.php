<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeTransformator extends Model
{
    use HasFactory;

    protected $table = 'type_transformator';

    protected $fillable = [
        'kapasitas_kva', 'tegangan_primer_kv', 'tegangan_sekunder_v', 'frekuensi_hz',
        'impedansi_percent', 'jumlah_phasa', 'pendingin', 'jenis_konstruksi', 'isolasi',
        'fco', 'arrester'
    ];
}


