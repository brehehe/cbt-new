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
        Schema::create('journal_items', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('journal_id');
            $table->foreignUuid('finance_id');
            $table->foreignUuid('finance_item_id')->nullable();
            $table->foreignUuid('finance_other_id')->nullable();
            $table->foreignUuid('finance_recipe_id')->nullable();
            $table->foreignUuid('finance_payment_id')->nullable();
            $table->foreignUuid('account_id')->nullable();
            $table->foreignUuid('company_id')->nullable();
            $table->string('code')->nullable();
            $table->string('type')->default('debit');
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
        Schema::dropIfExists('journal_items');
    }
};
