<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'role',
        'Phone',
        'Address',
        'otp',
        'otp_expired_at',
        'is_verified',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'otp',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'otp_expired_at' => 'datetime',
            'is_verified' => 'boolean',
        ];
    }

    /**
     * Relasi ke tabel komentar (1 user punya banyak komentar)
     */
    public function komentars()
    {
        return $this->hasMany(Komentar::class, 'user_id');
    }

    /**
     * Relasi ke tabel like_foto (1 user punya banyak like)
     */
    public function likes()
    {
        return $this->hasMany(LikeFoto::class, 'user_id');
    }

    /**
     * Relasi many-to-many ke tabel gallery (foto yang di-like oleh user)
     */
    public function likedGalleries()
    {
        return $this->belongsToMany(Gallery::class, 'like_foto', 'user_id', 'gallery_id')
                    ->withTimestamps();
    }

    /**
     * Cek apakah user adalah admin
     */
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    /**
     * Cek apakah user sudah verifikasi
     */
    public function isVerified()
    {
        return $this->is_verified === true;
    }

    /**
     * Scope untuk mengambil user yang sudah verifikasi
     */
    public function scopeVerified($query)
    {
        return $query->where('is_verified', true);
    }

    /**
     * Scope untuk mengambil user berdasarkan role
     */
    public function scopeByRole($query, $role)
    {
        return $query->where('role', $role);
    }
}