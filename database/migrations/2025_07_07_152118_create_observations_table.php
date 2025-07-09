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
        Schema::create('observations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('company_id')->nullable();
            $table->foreignUuid('practitioner_id')->nullable();
            $table->foreignUuid('patient_id')->nullable();
            $table->foreignUuid('encounter_id')->nullable();
            $table->string('category')->comment('Berisi satu atau lebih data dengan tipe data Coding. Nilainya mengacu pada data terminologi Observation Category Codes.');
            $table->string('code')->comment('Berisi satu atau lebih data dengan tipe data Coding. Nilainya mengacu pada data terminologi LOINC Codes.');
            $table->dateTime('effective_date_time')->nullable()->comment('Berisi data mengenai kapan observasi dilakukan');
            $table->dateTime('issued')->nullable()->comment('Berisi data tanggal dan waktu versi observasi ini tersedia, biasanya setelah hasilnya ditinjau/direview dan diverifikasi');
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
        Schema::dropIfExists('observations');
    }
};
