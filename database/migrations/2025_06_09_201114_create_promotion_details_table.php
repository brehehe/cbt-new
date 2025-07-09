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
        Schema::create('promotion_details', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('promotion_id');
            $table->foreignUuid('product_id');
            $table->string('name')->nullable();
            $table->string('description')->nullable();
            $table->decimal('hpp_average_real', 15, 2)->default(0);
            $table->decimal('hpp_average', 15, 2)->default(0);
            $table->decimal('selling_price_real', 15, 2)->default(0);
            $table->decimal('selling_price', 15, 2)->default(0);
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
        Schema::dropIfExists('promotion_details');
    }
};
