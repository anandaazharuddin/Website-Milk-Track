<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'content',
        'image',
        'author_id',
        'is_published',
    ];

    protected $casts = [
        'is_published' => 'boolean',
    ];

    // Relasi ke User (author)
    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    // Accessor untuk image URL
    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return asset('storage/' . $this->image);
        }
        return asset('assets/img/default-article.jpg');
    }
}