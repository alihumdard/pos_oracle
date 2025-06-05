<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('expense_category_id')->nullable()->constrained('expense_categories')->onDelete('set null'); // Nullable if category is optional or can be deleted
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null'); // User who recorded it
            $table->date('expense_date');
            $table->decimal('amount', 15, 2);
            $table->string('description');
            $table->string('paid_to')->nullable();
            $table->string('reference_number')->nullable(); // e.g., invoice, receipt no.
            $table->timestamps();
            $table->softDeletes(); // Optional
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};