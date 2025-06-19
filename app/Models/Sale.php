<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory; // Add if you use factories

class Sale extends Model
{
    use SoftDeletes, HasFactory; // Add HasFactory if you use it

    protected $guarded = [];

    protected $casts = [
        'transaction_id' => 'array',
        // 'customer_id' => 'array', // As per your original model
    ];

    public function customers() // Name changed from customers() to customer() if it's a belongsTo
    {
        // If a sale belongs to one customer and customer_id is a single ID
        if (!is_array($this->customer_id)) {
             return $this->belongsTo(Customer::class, 'customer_id', 'id');
        }
        // If customer_id can indeed be an array and you want to handle multiple customers
        // this relationship would be more complex (e.g., ManyToMany or custom)
        // For now, assuming it's meant to be a single customer or you'll handle the array manually.
        // If customer_id is cast to array in $casts and it's meant to be single, remove it from $casts.
        // If it is indeed single, the original customers() method with belongsTo is fine.
        // If Sale.customer_id is a single ID column (not JSON):
        // return $this->belongsTo(Customer::class, 'customer_id');
        return $this->belongsTo(Customer::class); // Assuming 'customer_id' is the foreign key
    }

    /**
     * Helper method to retrieve the Transaction models for this Sale.
     * This is not an Eloquent relationship for direct eager loading via `with()`.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getTransactionsCollection()
    {
        $transactionIds = $this->transaction_id; // Already an array due to $casts

        if (is_array($transactionIds) && !empty($transactionIds)) {
            return Transaction::whereIn('id', $transactionIds)->with('products')->get();
        }
        return collect(); // Return an empty collection if no IDs
    }
}