<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $table = 'transactions';
    
    protected $fillable = ['user_id', 'total_bayar', 'status_pembayaran', 'payment_method', 'snap_token_midtrans'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function details()
    {
        return $this->hasMany(TransactionDetail::class, 'transaction_id');
    }
}
