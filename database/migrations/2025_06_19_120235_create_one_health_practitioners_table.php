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
        Schema::create('one_health_practitioners', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('practitioner_id')->nullable();
            $table->string('id_practitiont')->nullable()->comment('ID practitiont dari satu sehat');
            $table->string('name_text')->comment('Berisi satu atau lebih data mengenai nama tenaga kesehatan dengan tipe data HumanName.');
            $table->string('name_use')->comment('');
            $table->date('birth_date')->comment('Berisi satu atau lebih data mengenai informasi tanggal lahir tenaga kesehatan dengan tipe data date.');
            $table->string('gender')->comment('Berisi satu atau lebih data mengenai informasi jenis kelamin tenaga kesehatan untuk keperluan administrasi dan pencatatan dengan tipe data code.');
            $table->string('full_url')->nullable();
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
        Schema::dropIfExists('one_health_practitioners');
    }
};
