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
        Schema::create('finance_others', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('finance_id');
            $table->string('name');
            $table->string('account_id')->nullable();
            $table->string('description')->nullable();
            $table->decimal('amount', 15, 2)->default(0);
            $table->string('type')->default('debit'); // debit or credit
            $table->enum('type_finance', ['first-service-price', 'second-service-price', 'rounding', 'admin-fee', 'payment-change', 'diskon', 'tax'])->default('expense');
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
        Schema::dropIfExists('finance_others');
    }
};
