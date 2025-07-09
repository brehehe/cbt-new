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
        Schema::create('product_categories', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name', 255);
            $table->text('description')->nullable();
            $table->tinyInteger('normal')->default(0); // For values 0-100
            $table->tinyInteger('recipe')->default(0); // For values 0-100
            $table->decimal('price', 10, 2); // 10 digits in total, 2 digits after the decimal
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
        Schema::dropIfExists('product_categories');
    }
};
