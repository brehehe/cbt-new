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
        Schema::create('master_consultation_category_conditions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('code');
            $table->string('display');
            $table->text('definition')->nullable();
            $table->string('code_system')->default('http://terminology.hl7.org/CodeSystem/condition-category')->comment('HL7 Condition Category Code System');
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
        Schema::dropIfExists('master_consultation_category_conditions');
    }
};
