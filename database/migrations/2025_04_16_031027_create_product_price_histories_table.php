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
        Schema::create('product_price_histories', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('product_id');
            $table->foreignUuid('product_price_id')->nullable();
            $table->foreignUuid('user_id')->nullable();
            $table->foreignUuid('branch_id');
            $table->foreignUuid('product_unit_id')->nullable();
            $table->foreignUuid('purchase_order_item_id')->nullable();
            $table->foreignUuid('transaction_detail_id')->nullable();
            $table->foreignUuid('product_import_stock_id')->nullable();
            $table->decimal('price', 15, 2)->default(0);           $table->decimal('quantity', 15, 2)->default(0);
            $table->decimal('sub_total_price', 15, 2)->default(0);
            $table->decimal('hpp_average', 15, 2)->default(0);
            $table->boolean('is_updated')->default(false);
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
        Schema::dropIfExists('product_price_histories');
    }
};
