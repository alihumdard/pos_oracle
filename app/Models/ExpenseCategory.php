<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // Add if using soft deletes

class ExpenseCategory extends Model
{
    use HasFactory, SoftDeletes; // Add SoftDeletes if used in migration

    protected $fillable = ['name', 'description']; // Or use guarded = []

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }
}