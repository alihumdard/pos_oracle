<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up()
{
    Schema::create('customer_recovery_dates', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('customer_id');
        $table->date('recovery_date');
        $table->boolean('is_active')->default(1); // 1 = Active, 0 = Inactive/History
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_recovery_dates');
    }
};
