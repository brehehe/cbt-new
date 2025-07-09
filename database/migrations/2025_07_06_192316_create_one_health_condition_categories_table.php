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
        Schema::create('one_health_condition_categories', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('one_health_condition_id')->nullable();
            $table->string('coding_system')->default('http://terminology.hl7.org/CodeSystem/condition-category')->comment('Berisi satu atau lebih data kode kategori kondisi apakah problem atau keluhan yang dirasakan pasien (diagnosis pasien) dengan tipe data Coding, yang nilainya mengacu pada data terminologi ConditionCategoryCodes.');
            $table->string('coding_code')->comment('Berisi satu atau lebih data kode kategori kondisi apakah problem atau keluhan yang dirasakan pasien (diagnosis pasien) dengan tipe data Coding, yang nilainya mengacu pada data terminologi ConditionCategoryCodes.');
            $table->string('coding_display')->comment('Berisi satu atau lebih data kode kategori kondisi apakah problem atau keluhan yang dirasakan pasien (diagnosis pasien) dengan tipe data Coding, yang nilainya mengacu pada data terminologi ConditionCategoryCodes.');
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
        Schema::dropIfExists('one_health_condition_categories');
    }
};
