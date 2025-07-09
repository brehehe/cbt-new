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
        Schema::create('locations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('company_id')->nullable();
            $table->foreignUuid('location_id')->nullable();
            $table->string('status')->default('active')->comment('Berisi data status lokasi dengan tipe data code, yang nilainya mengacu pada data terminologi LocationStatus.');
            $table->string('name')->comment('Berisi data nama lokasi dengan tipe data string.');
            $table->string('description')->comment('Berisi data deskripsi lokasi dengan tipe data string.');
            $table->string('mode')->default('instance')->comment('Berisi data mode lokasi dengan tipe data code, yang nilainya mengacu pada data terminologi LocationMode.');
            $table->string('physical_type')->default('ro')->comment('Berisi satu atau lebih daftar data mengenai informasi terkait tipe fisik lokasi dengan tipe data Coding.');
            $table->string('slug');
            $table->string('image')->nullable();
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
        Schema::dropIfExists('locations');
    }
};
