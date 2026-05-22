<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminActivity extends Model
{
    protected $fillable = [
        'admin_id',
        'admin_name',
        'admin_email',
        'action',
        'subject_type',
        'subject_id',
        'title',
        'description',
        'icon',
        'icon_bg',
        'icon_text',
    ];

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}
