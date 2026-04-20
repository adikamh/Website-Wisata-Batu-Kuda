<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionExtra extends Model
{
    protected $table = 'transaction_extras';
    
    protected $fillable = ['transaction_detail_id', 'extra_name', 'price_per_unit', 'quantity', 'subtotal'];
}