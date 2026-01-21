<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penyetoran extends Model
{
    use HasFactory;

    protected $table = 'penyetoran';

    protected $fillable = [
        'tanggal',
        'berat_jenis',
        'volume_liter',
        'nilai_uang',
        'status_kualitas',
    ];

    public function kualitas()
    {
        return $this->hasOne(KualitasSusu::class, 'penyetoran_id');
    }
}
