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
        Schema::create('one_health_practitiont_identifiers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('one_health_practitiont_id')->nullable();
            $table->string('system')->nullable()->comment('Di mana isi dari parameter');
            $table->string('use')->nullable()->comment('Berisi data dengan tipe data code, yang nilainya mengacu pada data terminologi IdentifierUse.');
            $table->string('value')->comment('BerIsi kode atau nomor');
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
        Schema::dropIfExists('one_health_practitiont_identifiers');
    }
};
