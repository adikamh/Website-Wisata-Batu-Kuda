<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionRentalItem extends Model
{
    protected $fillable = [
        'transaction_id',
        'rental_facility_id',
        'facility_name',
        'quantity',
        'price',
        'subtotal',
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function rentalFacility()
    {
        return $this->belongsTo(RentalFacility::class);
    }
}
