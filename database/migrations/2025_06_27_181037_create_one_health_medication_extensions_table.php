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
        Schema::create('one_health_medication_extensions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('one_health_medication_id')->nullable();
            $table->string('url')->default('https://fhir.kemkes.go.id/r4/StructureDefinition/MedicationType');
            $table->string('value_coding_system')->default('http://terminology.kemkes.go.id/CodeSystem/medication-type');
            $table->string('value_coding_code')->nullable();
            $table->string('value_coding_display')->nullable();
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
        Schema::dropIfExists('one_health_medication_extensions');
    }
};
