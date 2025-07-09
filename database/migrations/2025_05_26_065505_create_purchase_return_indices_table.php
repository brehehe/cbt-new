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
        Schema::create('purchase_return_indices', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('purchase_return_id');
            $table->foreignUuid('purchase_order_item_id');
            $table->foreignUuid('product_unit_id');
            $table->foreignUuid('product_id');
            $table->bigInteger('quantity')->default(0);
            $table->decimal('price', 15, 2)->default(0);
            $table->decimal('hna', 15, 2)->default(0);
            $table->decimal('ppn', 15, 2)->default(0);
            $table->decimal('hna_ppn', 15, 2)->default(0);
            $table->decimal('sub_total', 15, 2)->default(0);
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
        Schema::dropIfExists('purchase_return_indices');
    }
};
