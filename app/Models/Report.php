<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{

    protected $table = 'reports';
    
    protected $fillable = [
        'admin_id',
        'exported_by_name',
        'exported_by_email',
        'ip_address',
        'user_agent',
        'exported_at',
        'tipe_laporan',
        'file_url'
    ];
    
    protected $casts = [
        'exported_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
    
    public function scopeSalesReport($query)
    {
        return $query->where('tipe_laporan', 'sales_pdf')
                     ->orWhere('tipe_laporan', 'sales_excel');
    }
    
    public function scopeVisitorsReport($query)
    {
        return $query->where('tipe_laporan', 'visitors_pdf');
    }
    
    public function scopeOfType($query, $type)
    {
        return $query->where('tipe_laporan', $type);
    }
    
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('exported_at', [$startDate, $endDate]);
    }
    
    public function scopeToday($query)
    {
        return $query->whereDate('exported_at', today());
    }
    
    public function scopeThisWeek($query)
    {
        return $query->whereBetween('exported_at', [now()->startOfWeek(), now()->endOfWeek()]);
    }
    
    public function getTipeLaporanFormattedAttribute()
    {
        $types = [
            'sales_pdf' => 'Laporan Penjualan (PDF)',
            'visitors_pdf' => 'Laporan Kunjungan (PDF)',
            'excel' => 'Laporan Excel',
        ];
        
        return $types[$this->tipe_laporan] ?? ucfirst(str_replace('_', ' ', $this->tipe_laporan));
    }
    
    public function getFileUrlAttribute($value)
    {
        if (filter_var($value, FILTER_VALIDATE_URL)) {
            return $value;
        }
        
        return asset('storage/' . $value);
    }
}