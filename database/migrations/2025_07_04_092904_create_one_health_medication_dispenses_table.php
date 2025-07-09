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
        Schema::create('one_health_medication_dispenses', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('medication_dispense_id')->nullable();
            $table->string('id_medication_dispense')->nullable();
            $table->foreignUuid('one_health_organization_id')->nullable();
            $table->foreignUuid('one_health_location_id')->nullable();
            $table->foreignUuid('one_health_patient_id')->nullable();
            $table->foreignUuid('one_health_practitioner_id')->nullable();
            $table->foreignUuid('one_health_encounter_id')->nullable();
            $table->foreignUuid('one_health_medication_id')->nullable();
            $table->foreignUuid('one_health_medication_request_id')->nullable();
            $table->string('status')->default('completed')->comment('Berisi data yang berkaitan dengan kode spesifik yang menunjukkan status pengobatan saat ini yang umumnya akan berupa status aktif atau komplit dengan tipe data code yang nilainya mengacu pada MedicationDispense Status.');
            $table->string('medication_reference')->default('Medication/')->comment('Berisi data informasi obat yang diresepkan dengan tipe data Reference, yang direferensikan ke data yang tersimpan di resource Medication');
            $table->string('medication_display')->comment('Berisi data informasi obat yang diresepkan dengan tipe data Reference, yang direferensikan ke data yang tersimpan di resource Medication');
            $table->string('subject_reference')->default('Patient/')->comment('Berisi data informasi pasien yang diresepkan obat dengan tipe data Reference, yang direferensikan ke data yang tersimpan di resource Patient | Group');
            $table->string('subject_display')->comment('Berisi data informasi pasien yang diresepkan obat dengan tipe data Reference, yang direferensikan ke data yang tersimpan di resource Patient | Group');
            $table->string('context_reference')->default('Encounter/')->comment('Berisi data informasi terkait kunjungan di mana dispense obat dilakukan dengan tipe data Reference, yang direferensikan ke data yang tersimpan di resource Encounter | EpisodeOfCare');
            $table->string('location_reference')->default('Location/')->comment('Berisi data lokasi di mana obat diberikan dengan tipe data Reference, yang direferensikan ke data yang tersimpan di resource Location.id');
            $table->string('location_display')->comment('Berisi data lokasi di mana obat diberikan dengan tipe data Reference, yang direferensikan ke data yang tersimpan di resource Location.id');
            $table->string('authorizing_reference')->default('MedicationRequest/')->comment('Berisi data otorisasi resep dengan tipe data Reference, yang direferensikan ke data yang tersimpan di resource MedicationRequest.id');
            $table->string('quantity_system')->default('http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm')->comment('Berisi data jumlah obat yang dikeluarkan dalam bentuk numerical dengan tipe data SimpleQuantity, yang nilai satuan kekuatan zat aktif dapat mengacu pada data terminologi OrderableDrugForm (http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm) dan SNOMED CT.');
            $table->string('quantity_code')->comment('Berisi data jumlah obat yang dikeluarkan dalam bentuk numerical dengan tipe data SimpleQuantity, yang nilai satuan kekuatan zat aktif dapat mengacu pada data terminologi OrderableDrugForm (http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm) dan SNOMED CT.');
            $table->bigInteger('quantity_value')->comment('Berisi data jumlah obat yang dikeluarkan dalam bentuk numerical dengan tipe data SimpleQuantity, yang nilai satuan kekuatan zat aktif dapat mengacu pada data terminologi OrderableDrugForm (http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm) dan SNOMED CT.');
            $table->bigInteger('day_value')->comment('Berisi data jumlah pengobatan yang dinyatakan dalam satuan hari dengan tipe data SimpleQuantity.');
            $table->string('day_unit')->default('Day')->comment('Berisi data jumlah pengobatan yang dinyatakan dalam satuan hari dengan tipe data SimpleQuantity.');
            $table->string('day_system')->default('http://unitsofmeasure.org')->comment('Berisi data jumlah pengobatan yang dinyatakan dalam satuan hari dengan tipe data SimpleQuantity.');
            $table->string('day_code')->default('d')->comment('Berisi data jumlah pengobatan yang dinyatakan dalam satuan hari dengan tipe data SimpleQuantity.');
            $table->dateTime('when_prepare')->comment('Berisi data yang berkaitan dengan kapan obat dikemas dan dicek');
            $table->dateTime('when_hand_over')->comment('Berisi data yang berisikan data waktu pemberian obat kepada pasien atau penanggungjawab pasien');
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
        Schema::dropIfExists('one_health_medication_dispenses');
    }
};
