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
        Schema::create('encounter_class_histories', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('encounter_id')->nullable();
            $table->string('class_code')->comment('Berisi data klasifikasi dari pertemuan pasien dengan tipe data Coding, yang nilainya mengacu pada salah satu data terminologi dengan nama ActEncounterCode.');
            $table->dateTime('period_start')->nullable()->comment('Diisi dengan waktu mulai, sama dengan waktu dimulainya suatu klasifikasi kunjungan dalam format YYYY-MM-DD.');
            $table->dateTime('period_end')->nullable()->comment('Diisi dengan waktu selesai, sama dengan waktu berakhirnya suatu klasifikasi kunjungan dalam format YYYY-MM-DD.');
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
        Schema::dropIfExists('encounter_class_histories');
    }
};
