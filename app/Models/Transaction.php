<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $table = 'transactions';
    
    protected $fillable = [
        'user_id',
        'total_bayar',
        'status_pembayaran',
        'payment_method',
        'snap_token_midtrans',
        'xendit_invoice_id',
        'xendit_external_id',
        'xendit_invoice_url',
        'xendit_response',
        'camping_checked_out_at',
        'camping_trash_taken',
        'camping_actual_visitor_count',
        'camping_checked_in_at',
        'camping_checked_in_visitor_count',
        'camping_penalty',
        'camping_penalty_reason',
    ];

    protected $casts = [
        'camping_checked_out_at' => 'datetime',
        'camping_checked_in_at' => 'datetime',
        'camping_trash_taken' => 'boolean',
        'camping_actual_visitor_count' => 'integer',
        'camping_checked_in_visitor_count' => 'integer',
        'camping_penalty' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function details()
    {
        return $this->hasMany(TransactionDetail::class, 'transaction_id');
    }

    public function rentalItems()
    {
        return $this->hasMany(TransactionRentalItem::class, 'transaction_id');
    }
}
