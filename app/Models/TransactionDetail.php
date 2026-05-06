<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionDetail extends Model
{
    protected $table = 'transaction_details';
    
    protected $fillable = [
        'transaction_id', 'tiket_kategori_id', 'paket_id', 'quantity', 
        'subtotal', 'package_type', 'start_date', 'end_date', 'total_days',
        'extra_days', 'extra_days_charge', 'tax_amount', 'grand_total'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'transaction_id');
    }

    public function tiketKategori()
    {
        return $this->belongsTo(TiketKategori::class, 'tiket_kategori_id');
    }
}
