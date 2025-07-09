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
        Schema::create('transaction_payments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('transaction_id');
            $table->foreignUuid('user_id')->nullable();
            $table->foreignUuid('payment_method_id');
            $table->longText('description')->nullable();
            $table->double('admin_fee', 20, 2)->default(0);
            $table->double('payment_amount', 20, 2)->default(0);
            $table->double('payment_real', 20, 2)->default(0);
            $table->boolean('is_single_payment')->default(false);
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
        Schema::dropIfExists('transaction_payments');
    }
};
