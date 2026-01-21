<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KualitasSusu extends Model
{
    use HasFactory;

    protected $table = 'kualitas_susu';

    protected $fillable = [
        'penyetoran_id',
        'berat_jenis',
        'bau',
        'warna',
        'suhu',
        'status',
    ];

    public function penyetoran()
    {
        return $this->belongsTo(Penyetoran::class, 'penyetoran_id');
    }
}
