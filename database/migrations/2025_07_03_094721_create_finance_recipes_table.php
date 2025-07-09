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
        Schema::create('finance_recipes', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('finance_id')->nullable();
            $table->foreignUuid('transaction_recipe_id')->nullable();
            $table->foreignUuid('product_id')->nullable();
            $table->foreignUuid('medicine_type_id')->nullable();
            $table->decimal('price_service_one', 15, 2)->default(0);
            $table->string('product_name')->nullable();
            $table->decimal('numero_recipe', 15, 2)->default(0);
            $table->decimal('quantity', 15, 2)->default(0);
            $table->decimal('price', 15, 2)->default(0);
            $table->decimal('price_hpp', 15, 2)->default(0);
            $table->decimal('sub_total_price', 15, 2)->default(0);
            $table->decimal('sub_total_price_hpp', 15, 2)->default(0);
            $table->decimal('sub_total_price_ppn', 15, 2)->default(0);
            $table->decimal('sub_total_price_dpp', 15, 2)->default(0);
            $table->longText('description')->nullable();
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
        Schema::dropIfExists('finance_recipes');
    }
};
