<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TiketKategori extends Model
{
    protected $table = 'tiket_kategori';
    
    protected $fillable = ['wisata_id', 'nama_kategori', 'deskripsi', 'harga'];

    public function wisata()
    {
        return $this->belongsTo(Wisata::class, 'wisata_id');
    }

    public function transactionDetails()
    {
        return $this->hasMany(TransactionDetail::class, 'tiket_kategori_id');
    }
}
