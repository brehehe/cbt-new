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
        Schema::create('medication_request_dispense_requests', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('medication_request_id')->nullable();
            $table->foreignUuid('company_id')->nullable();
            $table->bigInteger('dispense_interval_value')->default(0)->comment('Berisi data yang Berkaitan dengan periode waktu minimal yang harus dilakukan antara pengeluaran obat');
            $table->string('dispense_interval_code')->comment('Berisi data yang Berkaitan dengan periode waktu minimal yang harus dilakukan antara pengeluaran obat');
            $table->dateTime('validity_start')->comment('Berisi data Periode waktu peresepan obat valid');
            $table->dateTime('validity_end')->comment('Berisi data Periode waktu peresepan obat valid');
            $table->bigInteger('number_repeat')->default(0)->comment('Berisi data Periode waktu peresepan obat valid');
            $table->bigInteger('quantity_value')->comment('Berisi data jumlah obat yang diberikan dalam 1 kali resep');
            $table->string('quantity_code')->comment('Berisi data jumlah obat yang diberikan dalam 1 kali resep');
            $table->bigInteger('expect_value')->comment('Berisi data identifikasi periode waktu selama produk yang diberikan diharapkan digunakan atau lamanya waktu pengeluaran yang diharapkan');
            $table->string('expect_code')->comment('Berisi data identifikasi periode waktu selama produk yang diberikan diharapkan digunakan atau lamanya waktu pengeluaran yang diharapkan');
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
        Schema::dropIfExists('medication_request_dispense_requests');
    }
};
