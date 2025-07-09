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
        Schema::create('one_health_medication_request_reason_codes', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('one_health_medication_request_id')->nullable();
            $table->string('coding_system')->default('http://hl7.org/fhir/sid/icd-10')->comment('Berisi data mengenai alasan atau indikasi untuk meminta atau tidak meminta pengobatan dengan tipe data Coding yang nilainya mengacu pada kode ICD-10 code versi 2010.');
            $table->string('coding_code')->comment('Berisi data mengenai alasan atau indikasi untuk meminta atau tidak meminta pengobatan dengan tipe data Coding yang nilainya mengacu pada kode ICD-10 code versi 2010.');
            $table->string('coding_display')->comment('Berisi data mengenai alasan atau indikasi untuk meminta atau tidak meminta pengobatan dengan tipe data Coding yang nilainya mengacu pada kode ICD-10 code versi 2010.');
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
        Schema::dropIfExists('one_health_medication_request_reason_codes');
    }
};
