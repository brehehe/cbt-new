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
        Schema::create('companies', function (Blueprint $table) {
            $table->uuid('id')->primary(); // UUID primary key
            $table->foreignUuid('company_id')->nullable(); // parent perusahan
            $table->foreignUuid('service_id')->nullable(); // parent perusahan
            $table->char('code', 6); // Kode perusahaan
            $table->string('name'); // Nama perusahaan
            $table->string('email'); // Email resmi
            $table->string('phone'); // Telepon
            $table->string('website')->nullable(); // Website

            // Informasi tambahan
            $table->string('logo')->nullable(); // Path logo (URL atau lokal)
            $table->string('tax_id')->nullable(); // NPWP / Tax ID
            $table->string('industry')->nullable(); // Industri
            $table->text('description')->nullable(); // Deskripsi perusahaan

            // PIC Information
            $table->string('pic_name')->nullable(); // Nama PIC
            $table->string('pic_position')->nullable(); // Jabatan PIC
            $table->string('pic_email')->nullable(); // Email PIC
            $table->string('pic_phone')->nullable(); // Telepon PIC

            // Status dan timestamps
            $table->boolean('is_active')->default(true);
            $table->timestamp('expires_at')->nullable(); // <-- Add expiration timestamp
            $table->bigInteger('duration_days')->default(0); // Durasi layanan dalam hari
            $table->date('start_date')->nullable(); // Tanggal mulai layanan
            $table->boolean('is_central')->default(false);
            $table->boolean('is_main')->default(false); //
            $table->boolean('is_lifetime')->default(false);

            //akses token
            $table->longText('one_health_access_token')->nullable()->comment('save auth access token');
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
        Schema::dropIfExists('companies');
    }
};
