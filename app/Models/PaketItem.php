<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaketItem extends Model
{
    protected $table = 'paket_items';
    
    protected $fillable = ['paket_id', 'nama_item', 'quantity'];
}