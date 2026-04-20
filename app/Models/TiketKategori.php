<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TiketKategori extends Model
{
    protected $table = 'tiket_kategori';
    
    protected $fillable = ['wisata_id', 'nama_kategori', 'harga'];
}