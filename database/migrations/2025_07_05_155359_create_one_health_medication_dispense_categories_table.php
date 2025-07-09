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
        Schema::create('one_health_medication_dispense_categories', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('one_health_medication_dispense_id')->nullable();
            $table->string('coding_system')->default('http://terminology.hl7.org/fhir/CodeSystem/medicationdispense-category');
            $table->string('coding_code')->nullable();
            $table->string('coding_display')->nullable();
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
        Schema::dropIfExists('one_health_medication_dispense_categories');
    }
};
