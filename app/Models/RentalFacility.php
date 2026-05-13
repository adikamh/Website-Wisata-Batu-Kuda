<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RentalFacility extends Model
{
    protected $fillable = [
        'nama_fasilitas',
        'deskripsi',
        'harga',
        'total_stok',
        'stok_tersedia',
        'is_active',
    ];

    protected $casts = [
        'harga' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function rentalItems()
    {
        return $this->hasMany(TransactionRentalItem::class);
    }
}
