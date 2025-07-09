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
        Schema::create('one_health_locations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('one_health_organization_id')->nullable();
            $table->foreignUuid('location_id')->nullable();
            $table->string('id_location')->nullable()->comment('ID location dari satu sehat');
            $table->string('status')->default('active')->comment('Berisi data status lokasi dengan tipe data code, yang nilainya mengacu pada data terminologi LocationStatus.');
            $table->string('name')->comment('Berisi data nama lokasi dengan tipe data string.');
            $table->string('description')->comment('Berisi data deskripsi lokasi dengan tipe data string.');
            $table->string('mode')->default('instance')->comment('Berisi data mode lokasi dengan tipe data code, yang nilainya mengacu pada data terminologi LocationMode.');
            $table->string('physicalType_coding_system')->default('http://terminology.hl7.org/CodeSystem/location-physical-type')->comment('Berisi satu atau lebih daftar data mengenai informasi terkait tipe fisik lokasi dengan tipe data Coding.');
            $table->string('physicalType_coding_code')->default('ro')->comment('Berisi satu atau lebih daftar data mengenai informasi terkait tipe fisik lokasi dengan tipe data Coding.');
            $table->string('physicalType_coding_display')->default('Room')->comment('Berisi satu atau lebih daftar data mengenai informasi terkait tipe fisik lokasi dengan tipe data Coding.');
            $table->string('position_longitude')->default(0)->comment('Berisi data informasi mengenai garis bujur dengan tipe data decimal.');
            $table->string('position_latitude')->default(0)->comment('Berisi data informasi mengenai garis lintang dengan tipe data decimal.');
            $table->string('position_altitude')->default(0)->comment('Berisi data informasi mengenai ketinggian dengan tipe data decimal.');
            $table->string('managing_organization_reference')->nullable()->comment('Berisi data organisasi pengelola lokasi dengan tipe data Reference, yang direferensikan ke data yang tersimpan di resource Organization.');
            $table->string('part_of_reference')->nullable()->comment('Berisi data lokasi bagian dari lokasi lain (sub lokasi) dengan tipe data Reference, yang direferensikan ke data yang tersimpan di resource Location');
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
        Schema::dropIfExists('one_health_locations');
    }
};
