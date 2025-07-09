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
        Schema::create('user_details', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id');
            $table->string('doctor_id')->nullable()->comment('ID dokter, jika pengguna adalah dokter');

            // Untuk semua jenis pengguna
            $table->string('province_code')->nullable()->comment('Kode provinsi by one health');
            $table->string('province')->nullable();  // Provinsi
            $table->string('city_code')->nullable()->comment('Kode kabupaten by one health');
            $table->string('city')->nullable(); // Kota/Kabupaten
            $table->string('district_code')->nullable()->comment('Kode kecamatan by one health');
            $table->string('district')->nullable(); // Kecamatan
            $table->string('sub_district_code')->nullable()->comment('Kode kelurahan by one health');
            $table->string('sub_district')->nullable(); // Kelurahan
            $table->string('postal_code')->nullable();
            $table->longText('address')->nullable()->comment('Alamat lengkap pengguna');
            $table->string('country')->default('ID');
            $table->string('rt', 5)->nullable()->comment('Kode RT by one health');
            $table->string('rw', 5)->nullable()->comment('Kode RW by one health');
            $table->string('longitude')->default(0)->comment('Kode longitude by one health');
            $table->string('latitude')->default(0)->comment('Kode latitude by one health');
            $table->string('altitude')->default(0)->comment('Kode altitude by one health');
            $table->string('ihs_number')->nullable()->comment('Nomor IHS untuk pasien (bisa kosong untuk non-pasien)');
            $table->longText('identity_card')->nullable()->comment('Foto / path file kartu identitas (KTP, BPJS, dll)');
            $table->string('blood_group')->nullable()->comment('Golongan darah (jika tersedia)');
            $table->string('administrative_gender')->nullable()->comment('Jenis kelamin administratif, mengacu pada terminologi AdministrativeGender');
            $table->date('birth_date')->nullable()->comment('Tanggal lahir');
            $table->date('deceased_date')->nullable()->comment('Tanggal kematian (jika pasien sudah meninggal)');
            $table->string('marital_status')->nullable()->comment('Status pernikahan sipil, mengacu pada terminologi Marital Status Codes');

            // Status pengguna secara umum
            $table->enum('status', ['active', 'non-active', 'block'])->default('active')->comment('Status akun pengguna');

            // Tambahan khusus untuk dokter
            $table->string('sip_number')->nullable()->comment('Nomor Surat Izin Praktik (hanya untuk dokter)');
            $table->string('specialization')->nullable()->comment('Spesialisasi dokter');
            $table->enum('doctor_type', ['general', 'specialist'])->default('general')->comment('Tipe dokter (umum atau spesialis)');
            $table->enum('type',['in','out'])->default('in')->comment('Tipe dokter (in house atau out house)');

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
        Schema::dropIfExists('user_details');
    }
};
