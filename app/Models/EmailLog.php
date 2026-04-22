<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailLog extends Model
{

    protected $table = 'email_logs';

    protected $fillable = [
        'e_ticket_id',
        'sent_to_email',
        'sent_at',
        'status',
        'error_message'
    ];
    
    protected $casts = [
        'sent_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    
    public function eTicket()
    {
        return $this->belongsTo(ETicket::class, 'e_ticket_id');
    }
    
    public function scopeSuccess($query)
    {
        return $query->where('status', 'success');
    }
  
    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }
    
    public function scopeSentTo($query, $email)
    {
        return $query->where('sent_to_email', $email);
    }
}