<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'role',
        'pos_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // ========== RELASI ==========
    
    /**
     * Relasi ke Pos Penyetoran
     */
    public function pos()
    {
        return $this->belongsTo(PosPenyetoran::class, 'pos_id');
    }

    // ========== HELPER ROLE ==========
    
    /**
     * Cek apakah user adalah Admin (Super Admin atau Admin Pos)
     */
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    /**
     * Cek apakah user adalah Super Admin (admin tanpa pos_id)
     */
    public function isSuperAdmin()
    {
        return $this->role === 'admin' && $this->pos_id === null;
    }

    /**
     * Cek apakah user adalah Admin Pos (admin dengan pos_id)
     */
    public function isAdminPos()
    {
        return $this->role === 'admin' && $this->pos_id !== null;
    }

    /**
     * Cek apakah user adalah Peternak
     */
    public function isPeternak()
    {
        return $this->role === 'peternak';
    }

    // ========== HELPER AKSES POS ==========
    
    /**
     * Cek apakah user dapat mengakses pos tertentu
     * 
     * @param int $posId
     * @return bool
     */
    public function canAccessPos($posId)
    {
        // Super admin (pos_id = null) bisa akses semua pos
        if ($this->isSuperAdmin()) {
            return true;
        }
        
        // Admin pos hanya bisa akses pos-nya sendiri
        return $this->pos_id == $posId;
    }
}