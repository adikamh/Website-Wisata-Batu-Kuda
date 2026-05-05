<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Komentar extends Model
{
    use HasFactory;

    protected $table = 'komentar';
    
    protected $fillable = [
        'gallery_id',
        'user_id',
        'isi_komentar',
    ];

    /**
     * Relasi ke tabel gallery (balikan)
     */
    public function gallery()
    {
        return $this->belongsTo(Gallery::class, 'gallery_id');
    }

    /**
     * Relasi ke tabel users (siapa yang komentar)
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Scope untuk filter komentar berdasarkan foto
     */
    public function scopeByGallery($query, $galleryId)
    {
        return $query->where('gallery_id', $galleryId);
    }

    
    public function scopeTerbaru($query)
    {
        return $query->orderBy('created_at', 'desc');
    }
}