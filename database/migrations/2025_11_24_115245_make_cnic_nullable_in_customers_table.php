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
        Schema::table('customers', function (Blueprint $table) {
            // Yahan hum keh rahe hain ke cnic column ko modify kar ke nullable bana do
            $table->string('cnic', 255)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            // Agar rollback karein to wapis not null ho jaye
            $table->string('cnic', 255)->nullable(false)->change();
        });
    }
};