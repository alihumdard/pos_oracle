<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Sale extends Model
{
    use SoftDeletes, HasFactory;

    protected $guarded = [];

    protected $casts = [
        'transaction_id' => 'array',
    ];

    public function customers()
    {
        if (!is_array($this->customer_id)) {
             return $this->belongsTo(Customer::class, 'customer_id', 'id');
        }
        return $this->belongsTo(Customer::class);
    }

    public function getTransactionsCollection()
    {
        $transactionIds = $this->transaction_id;

        if (is_array($transactionIds) && !empty($transactionIds)) {
            return Transaction::whereIn('id', $transactionIds)->with('products')->get();
        }
        return collect();
    }
}