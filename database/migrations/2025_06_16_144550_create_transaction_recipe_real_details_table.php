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
        Schema::create('transaction_recipe_real_details', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('transaction_recipe_real_id')->nullable();
            $table->foreignUuid('transaction_id')->nullable();
            $table->foreignUuid('transaction_detail_id')->nullable();
            $table->foreignUuid('product_id')->nullable();
            $table->string('product_name')->nullable();
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
        Schema::dropIfExists('transaction_recipe_real_details');
    }
};
