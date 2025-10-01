<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transformator extends Model
{
    use HasFactory;

    protected $table = 'transformator';

    protected $fillable = [
        'kode_aset','nomor_seri','merk','tahun_operasi','lokasi','koordinat_lat','koordinat_long',
        'penyulang','gardu','status','keterangan','type_id','gardu_induk_id'
    ];

    public function type()
    {
        return $this->belongsTo(TypeTransformator::class, 'type_id');
    }

    public function garduInduk()
    {
        return $this->belongsTo(GarduInduk::class, 'gardu_induk_id');
    }
}


