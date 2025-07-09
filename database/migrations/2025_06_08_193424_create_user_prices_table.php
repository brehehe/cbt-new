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
        Schema::create('user_prices', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id');
            $table->decimal('price_doctor', 15, 2)->nullable()->comment('Harga untuk dokter, bisa kosong jika tidak ada harga');
            $table->enum('type_incentive_doctor',['rupiah','persen'])->default('rupiah')->comment('Tipe insentif untuk dokter, bisa berupa rupiah atau persen');
            $table->enum('type_incentive_nurse',['rupiah','persen'])->default('rupiah')->comment('Tipe insentif untuk perawat/terapis, bisa berupa rupiah atau persen');
            $table->enum('type_incentive_pharmacy',['rupiah','persen'])->default('rupiah')->comment('Tipe insentif untuk apoteker, bisa berupa rupiah atau persen');
            $table->enum('type_incentive_cashier',['rupiah','persen'])->default('rupiah')->comment('Tipe insentif untuk kasir, bisa berupa rupiah atau persen');
            $table->decimal('incentive_doctor', 15, 2)->nullable()->comment('Incentive untuk dokter, bisa kosong jika tidak ada harga');
            $table->decimal('incentive_nurse', 15, 2)->nullable()->comment('Incentive untuk Perawat / Terapis, bisa kosong jika tidak ada harga');
            $table->decimal('incentive_pharmacy', 15, 2)->nullable()->comment('Incentive untuk Apoteker, bisa kosong jika tidak ada');
            $table->decimal('incentive_cashier', 15, 2)->nullable()->comment('Incentive untuk Kasir, bisa kosong jika tidak ada');
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
        Schema::dropIfExists('user_prices');
    }
};
