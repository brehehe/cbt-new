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
        Schema::create('one_health_enconter_participants', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('one_health_encounter_id')->nullable();
            $table->foreignUuid('one_health_practitioner_id')->nullable()->comment('id lokal untuk data practitiont');
            $table->string('type_coding_system')->default('http://terminology.hl7.org/CodeSystem/v3-ParticipationType');
            $table->string('type_coding_code')->nullable();
            $table->string('type_coding_display')->nullable();
            $table->string('individual_reference')->default('Practitioner/');
            $table->string('individual_display')->nullable();
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
        Schema::dropIfExists('one_health_enconter_participants');
    }
};
