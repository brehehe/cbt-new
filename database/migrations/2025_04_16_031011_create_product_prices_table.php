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
        Schema::create('product_prices', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('product_id');
            $table->foreignUuid('branch_id');
            $table->decimal('hpp_average', 15, 2);
            $table->decimal('hpp_average_generate', 15, 2)->default(0);
            $table->decimal('price_generate', 15, 2)->default(0);
            $table->decimal('price', 15, 2)->default(0);
            $table->decimal('recipe_generate', 15, 2)->default(0);
            $table->decimal('recipe', 15, 2)->default(0);
            $table->boolean('is_updated')->default(false);
            $table->foreignUuid('company_id');
            $table->softDeletes();
            $table->bigInteger('order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_prices');
    }
};
