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
        Schema::create('products', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('sku_number', 255)->nullable();
            $table->string('name', 255);
            $table->text('description')->nullable();
            $table->foreignUuid('product_category_id')->nullable();
            $table->foreignUuid('product_factory_id')->nullable();
            $table->foreignUuid('product_rack_id')->nullable();
            $table->foreignUuid('product_type_id')->nullable();
            $table->string('code_coding_code')->nullable();
            $table->string('form_coding_code')->nullable();
            $table->foreignUuid('company_id');
            $table->string('item_code')->nullable();
            $table->string('item_display')->nullable();
            $table->string('display')->nullable();
            $table->enum('registration_path', ['manual', 'sales', 'purchase', 'consignment', 'import'])->default('manual')->nullable();
            $table->boolean('is_narcotics')->nullable()->default(0);
            $table->boolean('is_non_stock')->nullable()->default(false);
            $table->integer('medicine_dosage')->default(0);
            $table->string('dosage_unit')->nullable();
            $table->integer('numerator_value')->default(0);
            $table->string('numerator_code')->nullable();
            $table->integer('denominator_value')->default(0);
            $table->string('denominator_code')->nullable();
            $table->foreignUuid('unit_id')->nullable();
            $table->tinyInteger('normal')->default(0); // For values 0-100
            $table->tinyInteger('recipe')->default(0); // For values 0-100
            $table->boolean('is_stock_ingredient')->default(false);
            $table->integer('minimun_stock')->nullable()->default(0);
            $table->integer('safety_stock')->nullable()->default(0);
            $table->integer('maximum_stock')->nullable()->default(0);
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
        Schema::dropIfExists('products');
    }
};
