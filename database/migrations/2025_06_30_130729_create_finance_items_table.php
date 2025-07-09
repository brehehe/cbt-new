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
        Schema::create('finance_items', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('finance_id')->nullable();
            $table->foreignUuid('finance_recipe_id')->nullable();
            $table->foreignUuid('transaction_recipe_id')->nullable();
            $table->foreignUuid('transaction_detail_id')->nullable();
            $table->foreignUuid('purchase_order_item_id')->nullable();
            $table->foreignUuid('dead_stock_id')->nullable();
            $table->foreignUuid('stock_opname_item_id')->nullable();
            $table->foreignUuid('import_stock_id')->nullable();
            $table->foreignUuid('account_id')->nullable();
            $table->foreignUuid('product_id')->nullable();
            $table->longText('description')->nullable();
            $table->decimal('quantity', 15, 2)->default(0);
            $table->decimal('price', 15, 2)->default(0);
            $table->decimal('price_hpp', 15, 2)->default(0);
            $table->decimal('tax', 15, 2)->default(0);
            $table->decimal('discount', 15, 2)->default(0);
            $table->decimal('sub_total', 15, 2)->default(0);
            $table->decimal('sub_total_hpp', 15, 2)->default(0);
            $table->decimal('sub_total_ppn', 15, 2)->default(0);
            $table->decimal('sub_total_dpp', 15, 2)->default(0);
            $table->decimal('loss_value', 15, 2)->default(0);
            $table->decimal('excess_value', 15, 2)->default(0);
            $table->foreignUuid('company_id')->nullable();
            $table->bigInteger('order')->default(0);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('finance_items');
    }
};
