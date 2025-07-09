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
        Schema::create('one_health_medication_ingredients', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('one_health_medication_id')->nullable();
            $table->foreignUuid('medication_ingredient_id')->nullable();
            $table->string('item_coding_system')->default('http://sys-ids.kemkes.go.id/kfa');
            $table->string('item_coding_code');
            $table->string('item_coding_display');
            $table->boolean('is_active')->default('true');
            $table->decimal('strength_numerator_value', 15);
            $table->string('strength_numerator_system')->default('http://unitsofmeasure.org');
            $table->string('strength_numerator_code')->comment('Berisi dari data master_value_quantities');
            $table->decimal('strength_denominator_value', 15);
            $table->string('strength_denominator_system')->default('http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm');
            $table->string('strength_denominator_code')->comment('erisi dari data master_value_quantities, master_orderable_drug_forms');
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
        Schema::dropIfExists('one_health_medication_ingredients');
    }
};
