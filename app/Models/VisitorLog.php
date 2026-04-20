<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VisitorLog extends Model
{
    protected $table = 'visitor_logs';
    
    protected $fillable = ['e_ticket_id', 'visitor_name', 'scanned_at'];
}