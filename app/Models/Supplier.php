<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supplier extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    protected $casts = [
        'debit'  => 'decimal:2',
        'credit' => 'decimal:2',
    ];

    // Reusable method for Ledger Balance calculation
    public function getRunningBalance($untilDate = null)
    {
        $endDate = $untilDate ?: now()->format('Y-m-d');

        // Total Purchases (Total Bill)
        $totalPurchases = $this->purchases()
            ->whereDate('purchase_date', '<=', $endDate)
            ->sum('total_amount');

        // Total Cash Paid at purchase time
        $totalPaidAtPurchase = $this->purchases()
            ->whereDate('purchase_date', '<=', $endDate)
            ->sum('cash_paid_at_purchase');

        // Total Separate Payments
        $totalExtraPayments = $this->payments()
            ->whereDate('payment_date', '<=', $endDate)
            ->sum('amount');

        // Logic: Total Bill - Total Payments (Positive = We owe, Negative = Supplier owes us)
        return $totalPurchases - ($totalPaidAtPurchase + $totalExtraPayments);
    }

    public function getBalanceAttribute()
    {
        return $this->debit - $this->credit;
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    public function payments()
    {
        return $this->hasMany(SupplierPayment::class);
    }
}