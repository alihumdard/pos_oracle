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
        return $this->hasMany(Sale::class, 'customer_id', 'id');
    }

    public function manualPayments()
    {
        return $this->hasMany(ManualPayment::class);
    }

    // --- RECOVERY DATES RELATIONSHIPS ---

    /**
     * Get all recovery dates for the customer history.
     */
    public function recoveryDates()
    {
        return $this->hasMany(CustomerRecoveryDate::class)->orderBy('id', 'desc');
    }

    /**
     * Get the specific single active recovery date (if any).
     */
    public function activeRecoveryDate()
    {
        return $this->hasOne(CustomerRecoveryDate::class)
                    ->where('is_active', 1)
                    ->latestOfMany();
    }
}