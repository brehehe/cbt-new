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
        Schema::create('one_health_encounter_locations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('one_health_encounter_id')->nullable();
            $table->foreignUuid('one_health_location_id')->nullable();
            $table->string('location_reference')->default('Location/')->comment('Berisi data lokasi dari pertemuan pasien. Dapat diisi oleh ruangan periksa pasien / poli pemeriksaannya dengan tipe data Reference');
            $table->string('location_display')->nullable()->comment('Berisi data lokasi dari pertemuan pasien. Dapat diisi oleh ruangan periksa pasien / poli pemeriksaannya dengan tipe data Reference');
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
        Schema::dropIfExists('one_health_encounter_locations');
    }
};
