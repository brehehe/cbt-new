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
        Schema::create('patient_contacts', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('patient_id');
            $table->string('name_use')->default('official')->comment('Berisi data nama penjamin dengan tipe data code.');
            $table->string('name_text')->comment('Berisi data nama penjamin dengan tipe data string.');
            $table->string('relationship_coding_code')->comment('Berisi data mengenai hubungan antara pasien dan orang yang dihubungi dengan tipe data Coding, yang nilainya mengacu pada data terminologi PatientContactRelationship.');
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->bigInteger('order')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patient_contacts');
    }
};
