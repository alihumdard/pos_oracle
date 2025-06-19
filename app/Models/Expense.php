<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // Add if using soft deletes

class Expense extends Model
{
    use HasFactory, SoftDeletes; // Add SoftDeletes if used in migration

    protected $fillable = [
        'expense_category_id',
        'user_id',
        'expense_date',
        'amount',
        'description',
        'paid_to',
        'reference_number',
    ];
    // Or use: protected $guarded = [];

    protected $casts = [
        'expense_date' => 'date',
        'amount' => 'decimal:2',
    ];

    public function category()
    {
        return $this->belongsTo(ExpenseCategory::class, 'expense_category_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class); // User who recorded it
    }
}