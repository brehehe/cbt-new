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
        Schema::create('transactions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('code')->unique();
            $table->string('code_consultation')->nullable();
            $table->foreignUuid('doctor_id')->nullable();
            $table->string('doctor_name')->nullable();
            $table->foreignUuid('location_id')->nullable();
            $table->string('location_name')->nullable();
            $table->date('date')->nullable();
            $table->string('day')->nullable();
            $table->foreignUuid('control_doctor_id')->nullable();
            $table->string('number_recipe')->nullable();
            $table->foreignUuid('patient_id')->nullable();
            $table->foreignUuid('patient_company_role_id')->nullable();
            $table->foreignUuid('payment_method_single_payment_id')->nullable();
            $table->decimal('single_payment_admin_fee', 15, 2)->default(0);
            $table->decimal('single_payment_payment_amount', 15, 2)->default(0);
            $table->decimal('single_payment_payment_real', 15, 2)->default(0);
            $table->boolean('is_single_payment')->default(false);
            $table->foreignUuid('branch_id')->nullable();
            $table->enum('type_customer', ['umum', 'member', 'new'])->default('umum');
            $table->enum('type_doctor', ['new', 'old'])->default('new');
            $table->string('patient_name')->nullable();
            $table->decimal('first_service_price', 15, 2)->default(0);
            $table->decimal('price_product_price', 15, 2)->default(0);
            $table->decimal('product_price', 15, 2)->default(0);
            $table->decimal('second_service_price', 15, 2)->default(0);
            $table->decimal('embalage', 15, 2)->default(0);
            $table->decimal('sub_total_price_embalage', 15, 2)->default(0);
            $table->decimal('sub_total_price', 15, 2)->default(0);
            $table->foreignUuid('discount_id')->nullable();
            $table->decimal('discount_real', 15, 2)->default(0);
            $table->decimal('discount', 15, 2)->default(0);
            $table->enum('discount_type', ['rupiah', 'percentage'])->default('rupiah');
            $table->decimal('discount_value', 15, 2)->default(0);
            $table->decimal('sub_total_price_before_rounding', 15, 2)->default(0);
            $table->decimal('rounding', 15, 2)->default(0);
            $table->decimal('rounding_remainder', 15, 2)->default(0);
            $table->decimal('grand_total_price', 15, 2)->default(0);
            $table->decimal('grand_total_price_admin_fee', 15, 2)->default(0);
            $table->decimal('payment_amount', 15, 2)->default(0);
            $table->decimal('payment_change', 15, 2)->default(0);
            $table->decimal('remaining_bill', 15, 2)->default(0);
            $table->foreignUuid('pharmacy_id')->nullable();
            $table->string('pharmacy_name')->nullable();
            $table->foreignUuid('cashier_id')->nullable();
            $table->string('cashier_name')->nullable();
            $table->foreignUuid('company_id')->nullable();
            $table->foreignUuid('created_by')->nullable();
            $table->enum('status', ['draft_consultation', 'call_consultation', 'confirmation_call', 'consultation', 'pharmacy', 'call_pharmacy', 'sale_pharmacy', 'draft', 'process', 'take_medicine', 'completed', 'canceled'])->default('draft');
            $table->boolean('is_take_medicine')->default(false);
            $table->enum('consultation', ['yes', 'no'])->default('no');
            $table->enum('pharmacy', ['yes', 'no'])->default('no');
            $table->enum('type', ['non-resep', 'resep', 'konsultasi'])->default('non-resep');
            $table->boolean('is_outside_pharmacy')->default(false);
            $table->date('date_prepare')->nullable();
            $table->longText('note')->nullable();
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
        Schema::dropIfExists('transactions');
    }
};
