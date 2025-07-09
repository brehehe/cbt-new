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
        Schema::create('one_health_organizations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('company_id')->nullable();
            $table->string('id_organization')->nullable()->comment('ID organisasi dari satu sehat');
            $table->boolean('active')->default(true)->comment('Berisi data status keaktifan data organisasi dengan tipe data boolean.');
            $table->string('type_coding_system')->default('http://terminology.hl7.org/CodeSystem/organization-type')->comment('Berisi data tipe organisasi dengan tipe data CodeableConcept.');
            $table->string('type_coding_code')->default('dept')->comment('Berisi data tipe organisasi dengan tipe data CodeableConcept.');
            $table->string('type_coding_display')->comment('Berisi data tipe organisasi dengan tipe data CodeableConcept.');
            $table->string('name')->comment("Berisi data nama organisasi dengan tipe data string.");
            $table->string('part_of_reference')->nullable()->comment("organisasi bagian dari organisasi lain (suborganisasi) dengan tipe data Reference, yang direferensikan ke data yang tersimpan di resource Organization");
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
        Schema::dropIfExists('one_health_organizations');
    }
};
