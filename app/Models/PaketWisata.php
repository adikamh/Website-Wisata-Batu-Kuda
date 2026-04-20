<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaketWisata extends Model
{
    protected $table = 'paket_wisata';
    
    protected $fillable = ['nama_paket', 'deskripsi_paket', 'harga_paket', 'is_active'];
}