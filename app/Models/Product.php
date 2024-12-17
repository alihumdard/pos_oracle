<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $guarded = [];
    public function transactions()
    {
    return $this->hasOne(Transaction::class, 'product_id', 'id');
    }
}
