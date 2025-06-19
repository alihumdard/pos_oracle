<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'purchase_date' => 'date',
        'unit_price' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'cash_paid_at_purchase' => 'decimal:2', // <-- ADDED THIS LINE
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}