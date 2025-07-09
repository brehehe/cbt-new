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
        Schema::create('purchase_order_items', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('purchase_order_id');
            $table->foreignUuid('purchase_requisition_item_id');
            $table->foreignUuid('purchase_return_index_id')->nullable();
            $table->foreignUuid('product_unit_id');
            $table->foreignUuid('product_id');
            $table->string('product_name');
            $table->bigInteger('quantity', 15, 2);
            $table->bigInteger('product_unit_quantity')->default(0);
            $table->bigInteger('quantity_bonus')->default(0);
            $table->bigInteger('quantity_accepted')->default(0);
            $table->bigInteger('quantity_less')->default(0);
            $table->bigInteger('quantity_return')->default(0);
            $table->bigInteger('quantity_total')->default(0);
            $table->bigInteger('quantity_detail')->default(0);
            $table->bigInteger('quantity_real')->default(0);
            $table->bigInteger('quantity_return_accepted')->default(0);
            $table->decimal('price', 15, 2)->default(0);
            $table->decimal('hna', 15, 2)->default(0);
            $table->decimal('ppn', 15, 2)->default(0);
            $table->decimal('hna_ppn', 15, 2)->default(0);
            $table->decimal('sub_total', 15, 2)->default(0);
            $table->decimal('discount', 15, 2)->default(0);
            $table->decimal('total', 15, 2)->default(0);
            $table->decimal('hna_total', 15, 2)->default(0);
            $table->decimal('hna_ppn_total', 15, 2)->default(0);
            $table->decimal('ppn_total', 15, 2)->default(0);
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
        Schema::dropIfExists('purchase_order_items');
    }
};
