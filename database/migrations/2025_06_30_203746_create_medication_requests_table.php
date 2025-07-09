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
        Schema::create('medication_requests', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('transaction_detail_id')->nullable();
            // $table->foreignUuid('company_id')->nullable();
            $table->foreignUuid('company_id')->nullable();
            $table->foreignUuid('patient_id')->nullable();
            $table->foreignUuid('encounter_id')->nullable();
            $table->foreignUuid('medication_id')->nullable();
            $table->string('status')->comment('Berisi data berkaitan dengan kode spesifik yang menunjukkan status pengobatan saat ini yang umumnya akan berupa status aktif atau komplit dengan tipe data code, yang nilainya mengacu pada data terminologi medicationrequest Status (http://hl7.org/fhir/CodeSystem/medicationrequest-status).');
            $table->string('intent')->comment('Berisi data berkaitan dengan tujuan pengobatan yang diresepkan apakah usulan, rencana, atau rencana pengobatan asli dengan tipe data code, yang nilainya mengacu pada data terminologi medicationRequest Intent (http://hl7.org/fhir/CodeSystem/medicationrequest-intent).');
            $table->string('category')->comment('Berisi data berkaitan dengan tipe permintaan pengobatan, seperti pengobatan yang diberikan/dikonsumsi pada rawat inap atau rawat jalan dengan tipe data CodeableConcept, yang nilainya mengacu pada data terminologi MedicationRequest Category Codes (http://terminology.hl7.org/CodeSystem/medicationrequest-category).');
            $table->string('priority')->comment('Berisi data yang mengindikasikan seberapa cepat permintaan pengobatan sebaiknya ditangani terkait dengan permintaan lainnya dengan tipe data code, yang nilainya mengacu pada data terminologi RequestPriority (http://hl7.org/fhir/request-priority).');
            $table->dateTime('author_on')->comment('Berisi data waktu peresepan dengan tipe data dateTime');
            $table->uuidMorphs('requestable');
            $table->string('reason_code')->comment('Berisi data mengenai alasan atau indikasi untuk meminta atau tidak meminta pengobatan dengan tipe data Coding yang nilainya mengacu pada kode ICD-10 code versi 2010.');
            $table->string('course_of_therapy')->comment('Berisi data yang mendeskripsikan keseluruhan pola pemberian obat pada pasien dengan tipe data Coding yang nilainya mengacu pada data terminologi MedicationRequest Course of Therapy Codes.');
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
        Schema::dropIfExists('medication_requests');
    }
};
