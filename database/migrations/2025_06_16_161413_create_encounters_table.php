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
        Schema::create('encounters', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('transaction_id')->nullable();
            $table->foreignUuid('company_id')->nullable();
            $table->foreignUuid('location_id')->nullable();
            $table->foreignUuid('patient_id')->nullable();
            $table->enum('type', ['outpatient', 'inpatient'])->default('outpatient')->comment('Type untuk kunjungan rawat jalan atau rawat inap');
            $table->string('status')->default('unknown')->comment('Berisi data status tahapan dari pertemuan pasien dengan tipe data code, yang nilainya mengacu pada data terminologi EncounterStatus');
            $table->string('class_code')->default('AMB')->comment('Berisi data klasifikasi dari pertemuan pasien dengan tipe data Coding, yang nilainya mengacu pada salah satu data terminologi dengan nama ActEncounterCode.');
            $table->dateTime('period_start')->nullable()->comment('Diisi dengan waktu mulai, sama dengan waktu kedatangan pasien dengan tipe data dateTime');
            $table->dateTime('period_end')->nullable()->comment('Diisi dengan waktu mulai, sama dengan waktu kepulangan pasien dengan tipe data dateTime');
            $table->string('hospital_discharge_text')->nullable()->comment('Catatan setelah pasien dipulangkan');
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
        Schema::dropIfExists('encounters');
    }
};
