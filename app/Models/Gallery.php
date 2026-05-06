<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Gallery extends Model
{
    use HasFactory;

    protected $table = 'gallery';
    
    protected $fillable = [
        'judul_foto',
        'deskripsi',
        'gambar_url',
    ];

    protected $appends = [
        'image_url',
    ];

    public function getImageUrlAttribute(): string
    {
        return self::resolveImageUrl($this->gambar_url);
    }

    public static function resolveImageUrl(?string $path): string
    {
        $path = trim((string) $path);

        if ($path === '') {
            return '';
        }

        if (Str::startsWith($path, ['http://', 'https://', '//'])) {
            return $path;
        }

        $path = ltrim($path, '/');

        if (Str::startsWith($path, 'storage/')) {
            $storagePath = Str::after($path, 'storage/');

            if (Storage::disk('public')->exists($storagePath)) {
                return route('gallery.image', ['path' => $storagePath]);
            }

            return asset($path);
        }

        if (Storage::disk('public')->exists($path)) {
            return route('gallery.image', ['path' => $path]);
        }

        return asset($path);
    }

    /**
     * Relasi ke tabel komentar (1 foto punya banyak komentar)
     */
    public function komentars()
    {
        return $this->hasMany(Komentar::class, 'gallery_id');
    }

    /**
     * Relasi ke tabel like_foto (1 foto punya banyak like)
     */
    public function likes()
    {
        return $this->hasMany(LikeFoto::class, 'gallery_id');
    }

    /**
     * Hitung total komentar
     */
    public function totalKomentar()
    {
        return $this->komentars()->count();
    }

    /**
     * Hitung total like
     */
    public function totalLike()
    {
        return $this->likes()->count();
    }

    /**
     * Cek apakah user sudah like foto ini
     */
    public function isLikedByUser($userId)
    {
        return $this->likes()->where('user_id', $userId)->exists();
    }

    /**
     * Ambil 5 komentar terbaru untuk ditampilkan
     */
    public function komentarTerbaru()
    {
        return $this->komentars()->with('user')->latest()->take(5);
    }
}
