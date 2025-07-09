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
        Schema::create('user_company_roles', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id');
            $table->foreignUuid('role_id');
            $table->foreignUuid('role_company_id')->nullable();
            $table->foreignUuid('company_id')->nullable();
            $table->string('medical_record_number')->nullable()->comment('Nomor rekam medis untuk pasien (bisa kosong untuk non-pasien)');
            $table->boolean('is_head')->default(false)->comment('Apakah role ini adalah kepala dari perusahaan atau tidak');
            $table->boolean('is_active')->default(true)->comment('Status aktif dari role ini');
            // $table->decimal('salary', 15, 2)->nullable()->comment('Gaji untuk role ini, bisa kosong jika tidak ada gaji');
            // $table->decimal('bonus', 15, 2)->nullable()->comment('Bonus untuk role ini, bisa kosong jika tidak ada bonus');
            // $table->decimal('commission', 15, 2)->nullable()->comment('Komisi untuk role ini, bisa kosong jika tidak ada komisi');
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
        Schema::dropIfExists('user_company_roles');
    }
};
