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
        Schema::create('one_health_patients', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('patient_id')->nullable();
            $table->string('id_patient')->nullable()->comment('id yang dapat dari respon satu sehat');
            $table->string('name_use')->default('official')->comment('Berisi data nama penjamin dengan tipe data code.');
            $table->string('name_text')->comment('Berisi data nama penjamin dengan tipe data string.');
            $table->string('gender')->nullable()->comment('Berisi data jenis kelamin pasien dengan tipe data code, yang nilainya mengacu pada salah satu data di terminologi dengan nama AdministrativeGender.');
            $table->date('birth_date')->nullable()->comment('Berisi data tanggal lahir pasien dengan tipe data date dalam format YYYY-MM-DD.');
            $table->date('deceased_date')->nullable()->comment('Berisi data yang menunjukkan apakah individu tersebut meninggal atau tidak dengan tipe data dateTime');
            $table->boolean('deceased_boolean')->default(false)->comment('Berisi data yang menunjukkan apakah individu tersebut meninggal atau tidak dengan tipe data boolean.');
            $table->boolean('active')->default(true)->comment('Berisi data apakah catatan pasien aktif digunakan dengan tipe data boolean.');
            $table->string('meta_profile')->default('https://fhir.kemkes.go.id/r4/StructureDefinition/Patient');
            $table->string('marital_status_coding_system')->default('http://terminology.hl7.org/CodeSystem/v3-MaritalStatus')->comment('Berisi data status perkawinan (sipil) terakhir pasien dengan tipe data Coding, yang nilainya mengacu pada data terminologi Marital Status Codes');
            $table->string('marital_status_coding_code')->nullable()->comment('Berisi data status perkawinan (sipil) terakhir pasien dengan tipe data Coding, yang nilainya mengacu pada data terminologi Marital Status Codes');
            $table->string('marital_status_coding_display')->nullable()->comment('Berisi data status perkawinan (sipil) terakhir pasien dengan tipe data Coding, yang nilainya mengacu pada data terminologi Marital Status Codes');
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
        Schema::dropIfExists('one_health_patients');
    }
};
