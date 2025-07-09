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
        Schema::create('one_health_encounters', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('encounter_id')->nullable();
            $table->foreignUuid('id_encounter')->nullable()->comment('id yang di peroleh dari satu sehat');
            $table->foreignUuid('one_health_organization_id')->nullable()->comment('id lokal untuk data organization');
            $table->foreignUuid('one_health_patient_id')->nullable()->comment('id lokal untuk data patient');
            $table->string('status')->comment('Berisi data status tahapan dari pertemuan pasien dengan tipe data code, yang nilainya mengacu pada data terminologi EncounterStatus');
            $table->string('class_system')->default('http://terminology.hl7.org/CodeSystem/v3-ActCode')->comment('Berisi data klasifikasi dari pertemuan pasien dengan tipe data Coding, yang nilainya mengacu pada salah satu data terminologi dengan nama ActEncounterCode.');
            $table->string('class_code')->comment('Berisi data klasifikasi dari pertemuan pasien dengan tipe data Coding, yang nilainya mengacu pada salah satu data terminologi dengan nama ActEncounterCode.');
            $table->string('class_display')->comment('Berisi data klasifikasi dari pertemuan pasien dengan tipe data Coding, yang nilainya mengacu pada salah satu data terminologi dengan nama ActEncounterCode.');
            $table->string('subject_reference')->default('Patient/')->comment('Berisi data subjek dari pertemuan pasien dengan tipe data Reference, yang direferensikan ke data yang tersimpan di resource Patient');
            $table->string('subject_display')->comment('Berisi data subjek dari pertemuan pasien dengan tipe data Reference, yang direferensikan ke data yang tersimpan di resource Patient');
            $table->string('period_start')->nullable()->comment('Diisi dengan waktu mulai, sama dengan waktu kedatangan pasien dengan tipe data dateTime');
            $table->string('period_end')->nullable()->comment('Diisi dengan waktu mulai, sama dengan waktu kepulangan pasien dengan tipe data dateTime');
            $table->string('service_provider_reference')->default('Organization/')->comment('berisi data organisasi pengelola lokasi dengan tipe data Reference, yang direferensikan ke data yang tersimpan di resource Organization. (id lokal one_health_organization_id)');
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
        Schema::dropIfExists('one_health_encounters');
    }
};
