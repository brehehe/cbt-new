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
        Schema::create('transaction_details', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('transaction_id')->nullable();
            $table->foreignUuid('transaction_recipe_id')->nullable();
            $table->foreignUuid('user_id')->nullable();
            $table->enum('type', ['partial', 'gramasi', 'single'])->default('single');
            $table->decimal('dosage_doctor', 20, 2)->default(0);
            $table->decimal('doctor_dosage_gram', 20, 2)->default(0);
            $table->bigInteger('dosage_drug')->default(0);
            $table->string('name')->nullable();
            $table->foreignUuid('product_id')->nullable();
            $table->foreignUuid('company_id')->nullable();
            $table->decimal('quantity_real', 20, 2)->default(0);
            $table->decimal('price', 20, 2)->default(0);
            $table->decimal('price_hpp', 20, 2)->default(0);
            $table->bigInteger('quantity')->default(0);
            $table->decimal('discount', 20, 2)->default(0);
            $table->decimal('sub_total_price', 20, 2)->default(0);
            $table->decimal('sub_total_price_hpp', 20, 2)->default(0);
            $table->boolean('is_narcotic')->default(false);
            $table->foreignUuid('user_asign_narcotic_id')->nullable();
            $table->enum('type_transaction', ['medicine', 'action', 'recipe', 'other'])->default('medicine');
            $table->boolean('is_outside_pharmacy')->default(false);
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
        Schema::dropIfExists('transaction_details');
    }
};
