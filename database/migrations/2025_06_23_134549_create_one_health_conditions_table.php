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
        Schema::create('one_health_conditions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('condition_id')->nullable();
            $table->string('id_condition')->nullable();
            $table->foreignUuid('one_health_organization_id')->nullable();
            $table->foreignUuid('one_health_patient_id')->nullable();
            $table->foreignUuid('one_health_encounter_id')->nullable();
            $table->string('subject_reference')->default('Patient/')->comment('Berisi data subjek dari kondisi dengan tipe data Reference, yang direferensikan ke data yang tersimpan di resource Patient | Group');
            $table->string('subject_display')->comment('Berisi data subjek dari kondisi dengan tipe data Reference, yang direferensikan ke data yang tersimpan di resource Patient | Group');
            $table->string('encounter_reference')->default('Encounter/')->comment('Berisi data informasi terkait kunjungan di mana diagnosis ditegakkan yang setiap datanya direpresentasikan dengan tipe data Reference yang direferensikan ke data yang tersimpan di resources Encounter');
            $table->string('encounter_display')->nullable()->comment('Berisi data informasi terkait kunjungan di mana diagnosis ditegakkan yang setiap datanya direpresentasikan dengan tipe data Reference yang direferensikan ke data yang tersimpan di resources Encounter');
            $table->dateTime('onset_date_time')->nullable()->comment('Berisi data mengenai kapan kondisi dimulai menurut pendapat dokter');
            $table->dateTime('recorded_date')->nullable()->comment('Berisi data kondisi yang menunjukkan kapan Kondisi/keluhan ini tercatat dalam sistem');
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
        Schema::dropIfExists('one_health_conditions');
    }
};
