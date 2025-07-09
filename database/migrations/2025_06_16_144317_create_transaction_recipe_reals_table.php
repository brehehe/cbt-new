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
        Schema::create('transaction_recipe_reals', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('transaction_id')->nullable();
            $table->foreignUuid('transaction_recipe_id')->nullable();
            $table->foreignUuid('product_id')->nullable();
            $table->string('product_name')->nullable();
            $table->foreignUuid('medicine_type_id')->nullable();
            $table->string('medicine_type_name')->nullable();
            $table->bigInteger('numero_recipe')->default(0);
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
        Schema::dropIfExists('transaction_recipe_reals');
    }
};
