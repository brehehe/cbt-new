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
        Schema::create('user_insurances', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id');
            $table->string('insurance_number')->nullable()->comment('Nomor asuransi pasien (jika ada)');
            $table->string('insurance_type')->nullable()->comment('Tipe asuransi pasien (jika ada)');
            $table->string('insurance_company')->nullable()->comment('Perusahaan asuransi pasien (jika ada)');
            $table->date('insurance_expiry_date')->nullable()->comment('Tanggal kedaluwarsa asuransi pasien (jika ada)');
            $table->string('insurance_status')->nullable()->comment('Status asuransi pasien (aktif/non-aktif)');
            $table->string('insurance_card')->nullable()->comment('Foto / path file kartu asuransi (jika ada)');
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
        Schema::dropIfExists('user_insurances');
    }
};
