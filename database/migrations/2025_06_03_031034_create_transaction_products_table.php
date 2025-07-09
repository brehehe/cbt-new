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
        Schema::create('transaction_products', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('transaction_id');
            $table->foreignUuid('user_id')->nullable();
            $table->string('user_name')->nullable();
            $table->foreignUuid('transaction_recipe_id')->nullable();
            $table->foreignUuid('transaction_detail_id')->nullable();
            $table->foreignUuid('product_id');
            $table->string('product_name');
            $table->bigInteger('quantity');
            $table->decimal('price', 10, 2)->default(0);
            $table->decimal('total', 10, 2)->default(0);
            $table->decimal('hpp_average', 10, 2)->default(0);
            $table->decimal('hpp_total', 10, 2)->default(0);
            $table->decimal('profit', 10, 2)->default(0);
            $table->tinyInteger('margin')->default(0);
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
        Schema::dropIfExists('transaction_products');
    }
};
