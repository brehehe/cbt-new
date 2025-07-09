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
        Schema::create('encounter_condition_icd10s', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('encounter_id');
            $table->foreignUuid('encounter_condition_id');
            $table->foreignUuid('transaction_icd10_id')->nullable();
            $table->foreignUuid('icd10_id');
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
        Schema::dropIfExists('encounter_condition_icd10s');
    }
};
