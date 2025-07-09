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
        Schema::create('patients', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->nullable();
            $table->string('ihs_number')->nullable();
            $table->string('blood_group')->nullable();

            $table->string('name');
            $table->date('birth_date')->nullable()->comment('Berisi data tanggal lahir pasien.');
            $table->string('gender')->nullable()->comment('Jenis kelamin');
            $table->date('deceased_date')->nullable()->comment('Berisi data yang menunjukkan apakah individu tersebut meninggal atau tidak.');
            $table->longText('identity_card')->nullable()->comment('Nomor NIK');
            $table->longText('passport_number')->nullable()->comment('Nomor Pasport');
            $table->longText('family_card_number')->nullable()->comment('Nomor Kartu keluarga');
            $table->string('marital_status')->nullable()->comment('Berisi data status perkawinan (sipil) terakhir pasien dengan tipe data Coding, yang nilainya mengacu pada data terminologi Marital Status Codes.');
            $table->enum('status', ['active', 'non-active', 'block'])->default('active');

            $table->string('phone')->nullable();
            $table->string('email')->nullable();

            $table->bigInteger('order')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};
