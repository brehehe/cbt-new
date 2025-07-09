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
        Schema::create('medication_dispenses', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('transaction_detail_id')->nullable();
            $table->foreignUuid('company_id')->nullable();
            $table->foreignUuid('location_id')->nullable();
            $table->foreignUuid('practitioner_id')->nullable();
            $table->foreignUuid('patient_id')->nullable();
            $table->foreignUuid('encounter_id')->nullable();
            $table->foreignUuid('medication_id')->nullable();
            $table->foreignUuid('medication_request_id')->nullable();
            $table->nullableUuidMorphs('performerable');
            $table->string('status')->default('completed')->comment('Berisi data yang berkaitan dengan kode spesifik yang menunjukkan status pengobatan saat ini yang umumnya akan berupa status aktif atau komplit dengan tipe data code yang nilainya mengacu pada MedicationDispense Status.');
            $table->string('category')->comment('Berisi satu atau lebih data yang berkaitan dengan tipe permintaan pengobatan, seperti pengobatan yang diberikan/dikonsumsi pada rawat inap atau rawat jalan dengan tipe data Coding, yang nilainya mengacu pada MedicationDispense category.');
            $table->bigInteger('quantity_value')->comment('Berisi data jumlah obat yang dikeluarkan dalam bentuk numerical dengan tipe data SimpleQuantity, yang nilai satuan kekuatan zat aktif dapat mengacu pada data terminologi OrderableDrugForm (http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm) dan SNOMED CT.');
            $table->string('quantity_code')->comment('Berisi data jumlah obat yang dikeluarkan dalam bentuk numerical dengan tipe data SimpleQuantity, yang nilai satuan kekuatan zat aktif dapat mengacu pada data terminologi OrderableDrugForm (http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm) dan SNOMED CT.');
            $table->bigInteger('day_value')->comment('Berisi data jumlah pengobatan yang dinyatakan dalam satuan hari dengan tipe data SimpleQuantity.');
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
        Schema::dropIfExists('medication_dispenses');
    }
};
