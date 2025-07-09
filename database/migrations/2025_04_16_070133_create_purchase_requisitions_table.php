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
        Schema::create('purchase_requisitions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('purchase_order_id')->nullable();
            $table->foreignUuid('purchase_return_id')->nullable();
            $table->foreignUuid('user_id');
            $table->string('number');
            $table->string('status')->default('draft');
            $table->foreignUuid('company_id');
            $table->foreignUuid('branch_id')->nullable();
            $table->foreignUuid('supplier_id')->nullable();
            $table->decimal('grand_total', 15, 2)->default(0);
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
        Schema::dropIfExists('purchase_requisitions');
    }
};
