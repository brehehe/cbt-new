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
        Schema::create('one_health_organization_identifiers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('one_health_organization_id');
            $table->string('use')->default('official')->comment('Berisi data dengan tipe data code, yang nilainya mengacu pada data terminologi IdentifierUse');
            $table->string('system')->default('http://sys-ids.kemkes.go.id/organization')->comment('Di mana isi dari parameter {organization-ihs-number} adalah ID organisasi induk yang didapatkan dari master sarana indeks.');
            $table->string('value')->comment('Berisi kode atau nomor internal sub organisasi. (value of one_health_organization.id)');
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
        Schema::dropIfExists('one_health_organization_identifiers');
    }
};
