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
        Schema::create('conditions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('transaction_condition_id')->nullable();
            $table->foreignUuid('company_id')->nullable();
            $table->foreignUuid('condition_id')->nullable();
            $table->foreignUuid('patient_id')->nullable();
            $table->foreignUuid('encounter_id')->nullable();
            $table->string('clinical_status')->comment('Berisi satu atau lebih data kode status klinis dari kondisi pasien dengan tipe data Coding, yang nilainya mengacu pada data terminologi ConditionClinicalStatusCodes.');
            $table->string('category')->comment('Berisi satu atau lebih data kode kategori kondisi apakah problem atau keluhan yang dirasakan pasien (diagnosis pasien) dengan tipe data Coding, yang nilainya mengacu pada data terminologi ConditionCategoryCodes.');
            $table->string('code')->comment('Berisi kode diagnosis dengan tipe data CodeableConcept, yang nilainya mengacu pada dua data terminologi ICD-10 tahun 2010 (untuk melaporkan terkait diagnosis pasien saat kunjungan) dan http://terminology.kemkes.go.id/CodeSystem/clinical-term (untuk melaporkan kondisi saat meninggalkan rumah sakit).');
            $table->dateTime('onset_date_time')->nullable()->comment('Berisi data mengenai kapan kondisi dimulai menurut pendapat dokter');
            $table->dateTime('recorded_date')->nullable()->comment('Berisi data kondisi yang menunjukkan kapan Kondisi/keluhan ini tercatat dalam sistem');
            $table->jsonb('notes')->nullable()->comment('Berisi satu atau lebih data informasi tambahan tentang Kondisi/ Keluhan/ Penyakit');
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
        Schema::dropIfExists('conditions');
    }
};
