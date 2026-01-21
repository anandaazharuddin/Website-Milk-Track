<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peternak extends Model
{
    use HasFactory;

    protected $table = 'peternak';

    protected $fillable = [
        'user_id',
        'kode_peternak',
        'nama_peternak',
        'pos_id',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pos()
    {
        return $this->belongsTo(PosPenyetoran::class, 'pos_id');
    }

    public function penyetoranHarian()
    {
        return $this->hasMany(PenyetoranHarian::class, 'peternak_id');
    }

    // ← HAPUS method generateKode()

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByPos($query, $posId)
    {
        return $query->where('pos_id', $posId);
    }
}