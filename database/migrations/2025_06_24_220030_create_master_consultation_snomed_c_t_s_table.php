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
        Schema::create('master_consultation_snomed_c_t_s', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('code');
            $table->string('display');
            $table->string('code_system')->default('http://snomed.info/sct')->comment('Snomed CT Code System');
            $table->longText('keterangan')->nullable();
            $table->enum('type', ['keluhan-utama', 'riwayat-penyakit'])->default('keluhan-utama')->comment('Type of Snomed CT, e.g., keluhan utama or riwayat penyakit');
            // $table->foreignUuid('company_id')->nullable();
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
        Schema::dropIfExists('master_consultation_snomed_c_t_s');
    }
};
