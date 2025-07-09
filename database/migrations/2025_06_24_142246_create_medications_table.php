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
        Schema::create('medications', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('product_id')->nullable();
            $table->foreignUuid('company_id')->nullable();
            $table->string('code_coding_code')->nullable()->comment('Berisi data kode obat yang digunakan akan menggunakan kode obat yang tersedia pada KFA (kamus farmasi dan alat kesehatan) dengan tipe data CodeableConcept.');
            $table->string('code_coding_display')->nullable()->comment('Berisi data kode obat yang digunakan akan menggunakan kode obat yang tersedia pada KFA (kamus farmasi dan alat kesehatan) dengan tipe data CodeableConcept.');
            $table->string('status')->default('active')->comment('Berisi data kode yang mengindikasikan pengobatan dalam penggunaan aktif dengan tipe data code, yang nilainya mengacu pada data terminologi Medication Status Codes.');
            $table->longText('manufacturer_reference')->nullable()->comment('Berisi data kode obat dengan tipe data Reference, yang direferensikan ke data yang tersimpan di resource Organization, yang menyimpan data pabrik obat.');
            $table->string('form_coding_code')->nullable()->comment('Berisi data yang menjelaskan bentuk dari sediaan obat yang merujuk pada Peraturan Kepala Badan Pengawas Obat dan Makanan Republik Indonesia Nomor 24 Tahun 2017 dengan tipe data Coding.');
            $table->string('form_coding_display')->nullable()->comment('Berisi data yang menjelaskan bentuk dari sediaan obat yang merujuk pada Peraturan Kepala Badan Pengawas Obat dan Makanan Republik Indonesia Nomor 24 Tahun 2017 dengan tipe data Coding.');
            $table->string('medication_type_code')->comment('Berisi satu atau lebih data bertipe Extension yang digunakan menyimpan informasi apakah obat yang diresepkan atau dikeluarkan merupakan obat non-racikan, obat racikan dengan instruksi berikan dalam dosis demikian/ d.t.d, atau obat racikan non-d.t.d, yang nilai dan strukturnya mengacu pada extension tambahan dengan nama MedicationType.');
            $table->string('medication_type_display')->nullable()->comment('Berisi satu atau lebih data bertipe Extension yang digunakan menyimpan informasi apakah obat yang diresepkan atau dikeluarkan merupakan obat non-racikan, obat racikan dengan instruksi berikan dalam dosis demikian/ d.t.d, atau obat racikan non-d.t.d, yang nilai dan strukturnya mengacu pada extension tambahan dengan nama MedicationType.');
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
        Schema::dropIfExists('medications');
    }
};
