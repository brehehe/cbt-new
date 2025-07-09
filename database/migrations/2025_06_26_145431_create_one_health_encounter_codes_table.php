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
        Schema::create('one_health_encounter_codes', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('one_health_encounter_id');
            $table->foreignUuid('encounter_condition_id')->nullable();
            $table->foreignUuid('encounter_condition_icd_10_id')->nullable();
            $table->string('code');
            $table->string('display');
            $table->longText('system')->default('http://snomed.info/sct')->nullable();
            $table->enum('type', ['snomed', 'icd'])->default('diagnosis');
            $table->foreignUuid('company_id')->nullable();
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
        Schema::dropIfExists('one_health_encounter_codes');
    }
};
