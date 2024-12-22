<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ManualPayment extends Model
{
    use SoftDeletes;
    protected $guarded=[];
    public function customer()
{
    return $this->belongsTo(Customer::class);
}

}
