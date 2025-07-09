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
        Schema::create('one_health_medication_request_course_therapies', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('one_health_medication_request_id')->nullable();
            $table->string('coding_system')->default('http://terminology.hl7.org/CodeSystem/medicationrequest-course-of-therapy')->comment('Berisi data yang mendeskripsikan keseluruhan pola pemberian obat pada pasien dengan tipe data Coding yang nilainya mengacu pada data terminologi MedicationRequest Course of Therapy Codes.');
            $table->string('coding_code')->comment('Berisi data yang mendeskripsikan keseluruhan pola pemberian obat pada pasien dengan tipe data Coding yang nilainya mengacu pada data terminologi MedicationRequest Course of Therapy Codes.');
            $table->string('coding_display')->comment('Berisi data yang mendeskripsikan keseluruhan pola pemberian obat pada pasien dengan tipe data Coding yang nilainya mengacu pada data terminologi MedicationRequest Course of Therapy Codes.');
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
        Schema::dropIfExists('one_health_medication_request_course_therapies');
    }
};
