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
        Schema::create('finance_payments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('finance_id');
            $table->foreignUuid('transaction_payment_id')->nullable();
            $table->foreignUuid('account_payment_id')->nullable();
            $table->foreignUuid('account_debt_id')->nullable();
            $table->decimal('amount', 15, 2)->default(0);
            $table->decimal('payment_real', 15, 2)->default(0);
            $table->decimal('total_loss_value', 15, 2)->default(0);
            $table->decimal('total_excess_value', 15, 2)->default(0);
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
        Schema::dropIfExists('finance_payments');
    }
};
