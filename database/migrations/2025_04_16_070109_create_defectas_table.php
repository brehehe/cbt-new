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
        Schema::create('defectas', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('product_stock_id');
            $table->foreignUuid('product_id');
            $table->foreignUuid('branch_id');
            $table->bigInteger('minimum_stock');
            $table->bigInteger('edited_minimum_stock')->nullable();
            $table->enum('status', ['new', 'need-approval', 'rejected', 'submitted'])->default('new');
            $table->foreignUuid('company_id')->nullable();
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
        Schema::dropIfExists('defectas');
    }
};
