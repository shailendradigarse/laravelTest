<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;
    protected $fillable = [
        'invoice_number',   // Add invoice_number for mass assignment
        'customer_id',
        'invoice_date',
        'amount',
        'tax',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
