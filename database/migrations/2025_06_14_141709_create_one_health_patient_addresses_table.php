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
        Schema::create('one_health_patient_addresses', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('one_health_patient_id')->nullable();
            $table->string('use')->default('home')->comment('Berisi data penggunaan alamat organisasi dengan tipe data code, yang nilainya mengacu pada data terminologi AddressUse.');
            $table->string('line')->nullable()->comment('Berisi satu atau lebih data nama, blok, no jalan atau no rumah dengan tipe data string.');
            $table->string('city')->nullable()->comment('Berisi satu atau lebih data mengenai nama kota, kotamadya, pinggiran kota, desa atau komunitas lain atau pusat pengiriman dengan tipe data string.');
            $table->string('postal_code')->nullable()->comment('Berisi data kode pos dengan tipe data string.');
            $table->string('country')->default('ID')->comment('Berisi data kode negara berdasarkan ISO 3316 2-letter (contoh: ID) dengan dengan tipe data string.');
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
        Schema::dropIfExists('one_health_patient_addresses');
    }
};
