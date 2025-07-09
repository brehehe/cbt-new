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
        Schema::create('one_health_patient_contact_relationships', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('one_health_patient_id')->nullable();
            $table->string('name_use')->default('official')->comment('Berisi data nama penjamin dengan tipe data code.');
            $table->string('name_text')->nullable()->comment('Berisi data nama penjamin dengan tipe data string.');
            $table->string('relationship_coding_system')->default('http://terminology.hl7.org/CodeSystem/v2-0131')->comment('Berisi data mengenai hubungan antara pasien dan orang yang dihubungi dengan tipe data Coding, yang nilainya mengacu pada data terminologi PatientContactRelationship.');
            $table->string('relationship_coding_code')->nullable()->comment('Berisi data mengenai hubungan antara pasien dan orang yang dihubungi dengan tipe data Coding, yang nilainya mengacu pada data terminologi PatientContactRelationship.');
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
        Schema::dropIfExists('one_health_patient_contact_relationships');
    }
};
