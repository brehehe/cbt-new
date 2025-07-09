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
        Schema::create('transaction_physical_examinations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('transaction_id')->nullable();
            $table->string('heart_rate')->nullable();
            $table->string('breathing')->nullable();
            $table->string('blood_pressure_sistole')->nullable();
            $table->string('blood_pressure_diastole')->nullable();
            $table->string('body_temperature')->nullable();
            $table->string('height')->nullable();
            $table->string('weight')->nullable();
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
        Schema::dropIfExists('transaction_physical_examinations');
    }
};
