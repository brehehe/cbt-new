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
        Schema::create('account_transactions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('finance_id');
            $table->foreignUuid('finance_item_id')->nullable();
            $table->foreignUuid('finance_other_id')->nullable();
            $table->foreignUuid('finance_recipe_id')->nullable();
            $table->foreignUuid('finance_payment_id')->nullable();
            $table->foreignUuid('journal_id')->nullable();
            $table->foreignUuid('journal_item_id')->nullable();
            $table->foreignUuid('account_id');
            $table->decimal('debit', 15, 2)->default(0);
            $table->decimal('credit', 15, 2)->default(0);
            $table->string('description')->nullable();
            $table->date('date')->nullable();
            $table->enum('type', ['debit', 'credit'])->default('debit');
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
        Schema::dropIfExists('account_transactions');
    }
};
