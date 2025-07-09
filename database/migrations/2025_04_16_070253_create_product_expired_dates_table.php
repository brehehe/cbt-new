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
        Schema::create('product_expired_dates', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('product_stock_id');
            $table->foreignUuid('product_id');
            $table->foreignUuid('branch_id');
            $table->date('expired_date');
            $table->string('batch_number');
            $table->integer('quantity');
            $table->foreignUuid('user_id')->nullable();
            $table->foreignUuid('company_id');
            $table->timestamps();
            $table->bigInteger('order')->default(0);
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_expired_dates');
    }
};
