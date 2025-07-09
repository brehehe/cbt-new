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
        Schema::create('transaction_recipes', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('transaction_id')->nullable();
            $table->foreignUuid('medicine_type_id')->nullable();
            $table->bigInteger('numero_recipe')->default(0);
            $table->decimal('price_service_one', 15, 2)->default(0);
            $table->foreignUuid('product_id')->nullable();
            $table->bigInteger('quantity')->default(0);
            $table->decimal('price', 15, 2)->default(0);
            $table->decimal('price_hpp', 15, 2)->default(0);
            $table->decimal('sub_total_price', 15, 2)->default(0);
            $table->decimal('sub_total_price_hpp', 15, 2)->default(0);
            $table->foreignUuid('how_to_use_id')->nullable();
            $table->longText('description')->nullable();
            $table->longText('route_coding_code')->nullable();
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
        Schema::dropIfExists('transaction_recipes');
    }
};
