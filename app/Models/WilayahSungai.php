<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WilayahSungai extends Model
{
    use HasFactory;
    protected $table = 'wilayah_sungai';
    protected $primaryKey = 'id';

    public function stasiun(): HasMany
    {
        return $this->hasMany(Stasiun::class, 'id', 'wilayah_sungai_id');
    }
}
