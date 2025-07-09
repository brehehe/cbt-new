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
        Schema::create('encounter_conditions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('encounter_id');
            $table->foreignUuid('transaction_id')->nullable();
            $table->foreignUuid('transaction_primary_id')->nullable();
            $table->longText('description')->nullable();
            $table->string('verification_status')->nullable();
            $table->string('clinical_status')->nullable();
            $table->string('snomed_code')->nullable();
            $table->string('onset_datetime')->nullable();
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
        Schema::dropIfExists('encounter_conditions');
    }
};
