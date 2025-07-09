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
        Schema::create('patient_contact_relationships', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('patient_id');
            $table->string('name')->nullable()->comment('Berisi data nama penjamin dengan tipe data string.');
            $table->string('relationship_coding_code')->nullable()->comment('Berisi data mengenai hubungan antara pasien dan orang yang dihubungi dengan tipe data Coding, yang nilainya mengacu pada data terminologi PatientContactRelationship.');
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
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
        Schema::dropIfExists('patient_contact_relationships');
    }
};
