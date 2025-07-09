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
        Schema::create('patient_addresses', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('patient_id');
            $table->string('use')->comment('Berisi data penggunaan alamat pasien dengan tipe data code, yang nilainya mengacu pada data terminologi AddressUse');
            $table->string('line')->comment('Berisi satu atau lebih data nama, blok, no jalan atau no rumah dengan tipe data');
            $table->string('city')->comment('Berisi satu data kota');
            $table->string('postal_code')->comment('Berisi data kode pos');
            $table->string('country')->comment('Berisi data kode negara berdasarkan ISO 3316 2-letter (contoh: ID)');
            $table->string('province_code')->comment('Berisi satu data kode provinsi');
            $table->string('city_code')->comment('Berisi satu data kode kota');
            $table->string('district_code')->comment('Berisi satu data kode kecamatan');
            $table->string('sub_district_code')->comment('Berisi satu data kode kelurahan');
            $table->string('rt')->comment('Berisi satu data nomor rt');
            $table->string('rw')->comment('Berisi satu data nomor rw');
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
        Schema::dropIfExists('patient_addresses');
    }
};
