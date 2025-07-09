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
        Schema::create('finances', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->char('code', 50)->unique();
            $table->foreignUuid('transaction_id')->nullable();
            $table->foreignUuid('purchase_order_id')->nullable();
            $table->foreignUuid('stock_opname_id')->nullable();
            // $table->foreignUuid('import_stock_id')->nullable();
            $table->enum('type', ['sale', 'purchase', 'dead-stock', 'stock-opname', 'import-stock', 'cost', 'expenditure', 'reception', 'fund-transfer', 'wage'])->default('cost');
            $table->longText('description')->nullable();
            $table->date('date')->nullable();
            $table->decimal('sub_total', 15, 2)->default(0);
            $table->decimal('discount', 15, 2)->default(0);
            $table->decimal('tax', 15, 2)->default(0);
            $table->decimal('single_payment_admin_fee', 15, 2)->default(0);
            $table->decimal('first_service_price', 15, 2)->default(0);
            $table->decimal('second_service_price', 15, 2)->default(0);
            $table->decimal('embalage', 15, 2)->default(0);
            $table->decimal('rounding', 15, 2)->default(0);
            $table->decimal('grand_total', 15, 2)->default(0);
            $table->decimal('payment_change', 15, 2)->default(0);
            $table->decimal('total_loss_value', 15, 2)->default(0);
            $table->decimal('total_excess_value', 15, 2)->default(0);
            $table->string('status')->default('confirmed'); // draft, confirmed, completed, cancelled
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
        Schema::dropIfExists('finances');
    }
};
