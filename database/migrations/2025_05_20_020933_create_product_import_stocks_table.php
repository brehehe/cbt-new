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
        Schema::create('product_import_stocks', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('product_id');
            $table->foreignUuid('product_type_id');
            $table->string('batch_number')->nullable();
            $table->date('expired_date')->nullable();
            $table->bigInteger('quantity')->default(0);
            $table->bigInteger('hpp_average')->default(0);
            $table->bigInteger('selling_price')->default(0);
            $table->bigInteger('selling_price_recipe')->default(0);
            $table->foreignUuid('branch_id')->nullable();
            $table->foreignUuid('company_id')->nullable();
            $table->boolean('is_process_finance')->default(false);
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
        Schema::dropIfExists('product_import_stocks');
    }
};
