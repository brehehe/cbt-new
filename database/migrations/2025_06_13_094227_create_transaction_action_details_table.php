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
        Schema::create('transaction_action_details', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('transaction_action_id');
            $table->foreignUuid('transaction_id');
            $table->foreignUuid('product_package_id')->nullable();
            $table->foreignUuid('product_id')->nullable();
            $table->bigInteger('quantity_real')->default(0);
            $table->bigInteger('quantity')->default(0);
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
        Schema::dropIfExists('transaction_action_details');
    }
};
