<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sale extends Model
{
    use SoftDeletes;
    protected $guarded=[];
    public function customers()
    {
        return $this->belongsTo(Customer::class, 'customer_id','id');
    }
   
}
