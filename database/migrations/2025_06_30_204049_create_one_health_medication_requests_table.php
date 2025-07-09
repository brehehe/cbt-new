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
        Schema::create('one_health_medication_requests', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('medication_request_id')->nullable();
            $table->foreignUuid('one_health_organization_id')->nullable();
            $table->foreignUuid('one_health_patient_id')->nullable();
            $table->foreignUuid('one_health_encounter_id')->nullable();
            $table->foreignUuid('one_health_medication_id')->nullable();
            $table->string('id_medication_request')->nullable();
            $table->string('status')->comment('Berisi data berkaitan dengan kode spesifik yang menunjukkan status pengobatan saat ini yang umumnya akan berupa status aktif atau komplit dengan tipe data code, yang nilainya mengacu pada data terminologi medicationrequest Status (http://hl7.org/fhir/CodeSystem/medicationrequest-status).');
            $table->string('intent')->comment('Berisi data berkaitan dengan tujuan pengobatan yang diresepkan apakah usulan, rencana, atau rencana pengobatan asli dengan tipe data code, yang nilainya mengacu pada data terminologi medicationRequest Intent (http://hl7.org/fhir/CodeSystem/medicationrequest-intent).');
            $table->string('priority')->comment('Berisi data yang mengindikasikan seberapa cepat permintaan pengobatan sebaiknya ditangani terkait dengan permintaan lainnya dengan tipe data code, yang nilainya mengacu pada data terminologi RequestPriority (http://hl7.org/fhir/request-priority).');
            $table->string('medication_reference')->default('Medication/')->comment('Berisi data informasi obat yang diresepkan dengan tipe data Reference, yang direferensikan ke data yang tersimpan di resource Medication');
            $table->string('medication_display')->comment('Berisi data informasi obat yang diresepkan dengan tipe data Reference, yang direferensikan ke data yang tersimpan di resource Medication');
            $table->string('subject_reference')->default('Patient/')->comment('Berisi data informasi pasien yang diresepkan obat dengan tipe data Reference, yang direferensikan ke data yang tersimpan di resource Patient | Group');
            $table->string('subject_display')->comment('Berisi data informasi pasien yang diresepkan obat dengan tipe data Reference, yang direferensikan ke data yang tersimpan di resource Patient | Group');
            $table->string('encounter_reference')->default('Encounter/')->comment('Berisi data informasi terkait kunjungan di mana peresepan obat dilakukan. WAJIB diisi apabila peresepan obat terjadi di rumah sakit dengan tipe data Reference, yang direferensikan ke data yang tersimpan di resource Encounter.');
            $table->dateTime('author_on')->comment('Berisi data waktu peresepan dengan tipe data dateTime');
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
        Schema::dropIfExists('one_health_medication_requests');
    }
};
