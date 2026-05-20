<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomepageContent extends Model
{
    protected $table = 'homepage_content';
    
    protected $fillable = [
        'about_title',
        'about_subtitle',
        'about_description',
        'features',
        'info_location',
        'info_opening_hours',
        'info_ticket_price',
        'info_contact',
        'tips',
    ];
    
    protected $casts = [
        'features' => 'json',
        'tips' => 'json',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
