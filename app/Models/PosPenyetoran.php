<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PosPenyetoran extends Model
{
    use HasFactory;

    protected $table = 'pos_penyetoran';

    protected $fillable = [
        'kode_pos',
        'nama_pos',
        'lokasi',
        'keterangan',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function peternak()
    {
        return $this->hasMany(Peternak::class, 'pos_id');
    }

    public function peternakAktif()
    {
        return $this->hasMany(Peternak::class, 'pos_id')->where('is_active', true);
    }

    public function penyetoranHarian()
    {
        return $this->hasMany(PenyetoranHarian::class, 'pos_id');
    }

    public static function generateKode()
    {
        $lastPos = self::orderBy('id', 'desc')->first();
        
        if ($lastPos) {
            $lastNumber = intval(substr($lastPos->kode_pos, 3));
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        
        return 'POS' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}