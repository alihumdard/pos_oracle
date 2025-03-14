<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;
    protected $guarded = [];
    public function transactions()
    {
    return $this->hasOne(Transaction::class, 'product_id', 'id');
    }
    public function supplier()
    {
    return $this->belongsTo(Supplier::class, 'supplier_id', 'id');
    }
    public function category()
    {
    return $this->belongsTo(Category::class, 'category_id', 'id');
    }
   
}
