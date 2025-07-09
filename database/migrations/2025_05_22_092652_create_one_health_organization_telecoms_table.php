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
        Schema::create('one_health_organization_telecoms', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('one_health_organization_id');
            $table->string('system')->comment('Berisi data jenis kontak dengan tipe data code, yang nilainya mengacu pada data terminologi ContactPointSystem.');
            $table->string('value')->comment('Berisi data nomor/email/website kontak organisasi dengan tipe data string.');
            $table->string('use')->default('work')->comment('Berisi data penggunaan kontak organisasi dengan tipe data code, yang nilainya mengacu pada data terminologi ContactPointUse.');
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
        Schema::dropIfExists('one_health_organization_telecoms');
    }
};
