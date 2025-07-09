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
        Schema::create('one_health_practitiont_addresses', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('one_health_practitiont_id')->nullable();
            $table->string('use')->default('home')->comment('Berisi data penggunaan alamat dengan tipe data code.');
            $table->string('line')->comment('Berisi data alamat lengkap tenaga kesehatan dengan tipe data string.');
            $table->string('city')->comment('Berisi satu atau lebih data mengenai nama kota, kotamadya, pinggiran kota, desa atau komunitas lain atau pusat pengiriman dengan tipe data string.');
            $table->string('country')->default('ID')->comment('Berisi data kode negara berdasarkan ISO 3316 2-letter (contoh: ID) dengan tipe data string.');
            $table->string('postal_code')->nullable()->comment('Berisi data kode pos yang menunjuk wilayah yang ditentukan oleh layanan pos dengan tipe data string.');
            $table->string('extention_url')->default('https://fhir.kemkes.go.id/r4/StructureDefinition/administrativeCode');
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
        Schema::dropIfExists('one_health_practitiont_addresses');
    }
};
