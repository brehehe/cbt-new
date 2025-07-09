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
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('code')->nullable();
            $table->enum('type', ['rupiah', 'percentage'])->default('rupiah');
            $table->double('value', 20, 2)->default(0);
            $table->enum('type_admin_fee', ['rupiah', 'percentage'])->default('rupiah');
            $table->double('value_admin_fee')->default(0);
            $table->boolean('is_offline_payment')->default(false);
            $table->boolean('is_single_payment')->default(false);
            $table->foreignUuid('account_id')->nullable();
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
        Schema::dropIfExists('payment_methods');
    }
};
