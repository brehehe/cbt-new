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
        Schema::create('purchase_requisition_items', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('purchase_requisition_id')->nullable();
            $table->foreignUuid('branch_id')->nullable();
            $table->foreignUuid('company_id')->nullable();
            $table->foreignUuid('product_id');
            $table->string('product_name');
            $table->foreignUuid('unit_id')->nullable();
            $table->foreignUuid('product_unit_id')->nullable();
            $table->bigInteger('quantity');
            $table->bigInteger('quantity_detail')->default(0);
            $table->bigInteger('quantity_real')->default(0);
            $table->string('status')->default('draft');
            $table->timestamps();
            $table->bigInteger('order')->default(0);
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_requisition_items');
    }
};
