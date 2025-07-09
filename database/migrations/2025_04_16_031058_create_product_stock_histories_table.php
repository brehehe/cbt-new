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
        Schema::create('product_stock_histories', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('product_id');
            $table->foreignUuid('product_stock_id')->nullable();
            $table->foreignUuid('product_unit_id')->nullable();
            $table->foreignUuid('purchase_order_item_id')->nullable();
            $table->foreignUuid('transaction_detail_id')->nullable();
            $table->foreignUuid('transaction_recipe_id')->nullable();
            $table->foreignUuid('product_import_stock_id')->nullable();
            $table->foreignUuid('dead_stock_id')->nullable();
            $table->foreignUuid('user_id')->nullable();
            $table->foreignUuid('branch_id');
            $table->string('code');
            $table->string('description');
            $table->foreignUuid('reference')->nullable(); // Referensi (no faktur, no nota, dll)
            $table->date('date');
            $table->bigInteger('quantity');
            $table->enum('type', ['in', 'out'])->default('in');
            $table->decimal('price', 15, 2);
            $table->decimal('sub_total_price', 15, 2);
            $table->foreignUuid('company_id');
            $table->softDeletes();
            $table->bigInteger('order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_stock_histories');
    }
};
