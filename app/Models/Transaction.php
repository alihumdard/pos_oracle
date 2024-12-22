<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $guarded = [];
    public function products()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
    public function sale()
    {
        return $this->belongsTo(Sale::class, 'transaction_id', 'id');
    }
}
