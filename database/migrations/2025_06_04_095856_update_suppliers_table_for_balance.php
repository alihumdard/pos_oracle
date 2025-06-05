<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('suppliers', function (Blueprint $table) {
            // Place these columns appropriately, e.g., after 'note' or another existing column
            $table->decimal('debit', 15, 2)->default(0)->after('note')->comment('Amount supplier owes us');
            $table->decimal('credit', 15, 2)->default(0)->after('debit')->comment('Amount we owe supplier');
        });
    }

    public function down(): void
    {
        Schema::table('suppliers', function (Blueprint $table) {
            $table->dropColumn(['debit', 'credit']);
        });
    }
};