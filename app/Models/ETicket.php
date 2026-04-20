<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ETicket extends Model
{
    protected $table = 'e_tickets';
    
    protected $fillable = ['transaction_detail_id', 'ticket_code', 'qr_code_hash', 'watermark_path', 'is_used', 'validated_at'];
}