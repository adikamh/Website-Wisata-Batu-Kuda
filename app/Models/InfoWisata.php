<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InfoWisata extends Model
{
    use HasFactory;
    protected $table = 'info_wisata';

    protected $fillable = [
        'judul',
        'kategori',
        'icon',
        'deskripsi',
        'poin',
        'gambar',
        'urutan',
    ];

    protected $casts = [
        'poin'   => 'array',
        'gambar' => 'array',
    ];

    /**
     * Default ordering by urutan then created_at
     */
    protected static function booted(): void
    {
        static::creating(function ($model) {
            if (is_null($model->poin))   $model->poin   = [];
            if (is_null($model->gambar)) $model->gambar = [];
        });
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('urutan')->orderBy('created_at');
    }
}