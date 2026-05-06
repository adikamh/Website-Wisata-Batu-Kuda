<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LikeFoto extends Model
{
    use HasFactory;

    protected $table = 'like_foto';
    
    protected $fillable = [
        'gallery_id',
        'user_id',
    ];

    /**
     * Relasi ke tabel gallery (foto yang dilike)
     */
    public function gallery()
    {
        return $this->belongsTo(Gallery::class, 'gallery_id');
    }

    /**
     * Relasi ke tabel users (user yang like)
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

  
    public function scopeUserLike($query, $galleryId, $userId)
    {
        return $query->where('gallery_id', $galleryId)
                     ->where('user_id', $userId);
    }
}