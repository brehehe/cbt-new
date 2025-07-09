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
        Schema::create('patient_companies', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('patient_id');
            $table->foreignUuid('company_id');
            $table->text('medical_number_record')->nullable();
            $table->softDeletes();
            $table->bigInteger('order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patient_companies');
    }
};
