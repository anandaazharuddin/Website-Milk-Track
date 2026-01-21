<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenyetoranHarian extends Model
{
    use HasFactory;

    protected $table = 'penyetoran_harian';

    protected $fillable = [
        'peternak_id',
        'pos_id',
        'tanggal',
        'volume_pagi',
        'bj_pagi',
        'volume_sore',
        'bj_sore',
        'catatan',
        'created_by'
    ];

    protected $casts = [
        'tanggal' => 'date',
        'volume_pagi' => 'decimal:2',
        'volume_sore' => 'decimal:2',
        'bj_pagi' => 'integer', // ← Simpan sebagai integer
        'bj_sore' => 'integer', // ← Simpan sebagai integer
    ];

    public function peternak()
    {
        return $this->belongsTo(Peternak::class, 'peternak_id');
    }

    public function pos()
    {
        return $this->belongsTo(PosPenyetoran::class, 'pos_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Helper untuk format BJ (1234 → 1.234)
    public function getBjPagiFormattedAttribute()
    {
        return $this->bj_pagi ? number_format($this->bj_pagi / 1000, 3) : null;
    }

    public function getBjSoreFormattedAttribute()
    {
        return $this->bj_sore ? number_format($this->bj_sore / 1000, 3) : null;
    }

    public function getTotalVolumeAttribute()
    {
        return ($this->volume_pagi ?? 0) + ($this->volume_sore ?? 0);
    }

    public function scopeByTanggal($query, $tanggal)
    {
        return $query->where('tanggal', $tanggal);
    }

    public function scopeByPos($query, $posId)
    {
        return $query->where('pos_id', $posId);
    }
}