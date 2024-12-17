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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('category_id');
            $table->string('supplier_id');
            $table->string('item_code', 200)->nullable();
            $table->string('gen_name', 200)->nullable();
            $table->string('item_name', 200)->nullable();
            $table->string('profit', 100)->nullable();
            $table->string('selling_price', 100)->nullable();
            $table->string('original_price', 100)->nullable();
            $table->string('packeges', 100)->nullable();
            $table->string('packing_price', 100)->nullable();
            $table->string('packing_qty', 100)->nullable();
            $table->integer('discount')->nullable();
            $table->integer('qty_sold')->nullable();
            $table->integer('qty')->nullable();
            $table->string('expiry_date', 500)->nullable();
            $table->string('date_arrival')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
