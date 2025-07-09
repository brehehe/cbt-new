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
        Schema::create('patient_identifiers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('patient_id');
            $table->string('use')->comment('Berisi data dengan tipe data code, yang nilainya mengacu pada data terminologi IdentifierUse');
            $table->string('system')->comment('Berisi data yang nilainya memiliki format : nik, paspor, kk');
            $table->longText('value')->comment('Berisi kode atau nomor pasien.');
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
        Schema::dropIfExists('patient_identifiers');
    }
};
