<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('customer_recovery_dates', function (Blueprint $table) {
            // Adds a boolean column 'is_received' with a default value of 0 (false)
            // We place it after 'is_active' to keep the table organized
            $table->boolean('is_received')->default(0)->after('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customer_recovery_dates', function (Blueprint $table) {
            $table->dropColumn('is_received');
        });
    }
};