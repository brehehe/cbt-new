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
        Schema::create('encounter_practitionts', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('encounter_id')->nullable();
            $table->foreignUuid('practitioner_id')->nullable();
            $table->string('type_coding_code')->default('ATND')->comment('Berisi satu atau lebih data partisipan pertemuan pasien dengan tipe data Coding, yang nilainya mengacu pada salah satu data terminologi dengan nama ParticipantType.');
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
        Schema::dropIfExists('encounter_practitionts');
    }
};
