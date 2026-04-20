<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wisata extends Model
{
    protected $table = 'wisata';
    
    protected $fillable = [
        'nama_wisata', 
        'deskripsi', 
        'lokasi', 
        'gambar_url'
    ];
}