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
        Schema::create('promotions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('code')->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->enum('type_promotion', ['percentage', 'fixed_amount', 'buy_x_get_y', 'free_shipping', 'bundle']);

            // Untuk promo percentage atau fixed amount
            $table->decimal('discount_value', 10, 2)->nullable();
            $table->decimal('max_discount', 10, 2)->nullable();

            // Untuk promo buy x get y
            $table->integer('buy_quantity')->nullable();
            $table->integer('get_quantity')->nullable();
            $table->unsignedBigInteger('buy_product_id')->nullable();
            $table->unsignedBigInteger('get_product_id')->nullable();

            // Syarat dan ketentuan
            $table->decimal('min_purchase', 10, 2)->default(0);
            $table->integer('max_usage')->nullable();
            $table->integer('used_count')->default(0);

            // Periode promo
            $table->datetime('start_date');
            $table->datetime('end_date');

            // Target customer
            $table->enum('target_customer', ['all', 'new_customer', 'returning_customer', 'specific_customer']);
            $table->json('customer_ids')->nullable(); // untuk specific customer

            // Status
            $table->boolean('is_active')->default(true);
            $table->boolean('is_stackable')->default(false); // bisa dikombinasi dengan promo lain

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
        Schema::dropIfExists('promotions');
    }
};
