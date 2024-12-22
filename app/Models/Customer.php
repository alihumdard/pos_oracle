<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use SoftDeletes;
    protected $guarded = [];
    public function sales()
    {
        return $this->hasMany(Sale::class,'customer_id','id');
    }
    public function manualPayments()
{
    return $this->hasMany(ManualPayment::class);
}

}
