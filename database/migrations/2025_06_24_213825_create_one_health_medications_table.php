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
        Schema::create('one_health_medications', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('medication_id')->nullable();
            $table->string('id_medication')->nullable();
            $table->string('meta_profile')->default('https://fhir.kemkes.go.id/r4/StructureDefinition/Medication');
            $table->string('status')->default('active')->comment('Berisi data kode yang mengindikasikan pengobatan dalam penggunaan aktif dengan tipe data code, yang nilainya mengacu pada data terminologi Medication Status Codes.');
            $table->longText('manufacturer_reference')->nullable()->comment('Berisi data kode obat dengan tipe data Reference, yang direferensikan ke data yang tersimpan di resource Organization, yang menyimpan data pabrik obat.');
            // $table->string('extention_medication_type')->comment('Berisi satu atau lebih data bertipe Extension yang digunakan menyimpan informasi apakah obat yang diresepkan atau dikeluarkan merupakan obat non-racikan, obat racikan dengan instruksi berikan dalam dosis demikian/ d.t.d, atau obat racikan non-d.t.d, yang nilai dan strukturnya mengacu pada extension tambahan dengan nama MedicationType.');
            // $table->string('extention_medication_display')->comment('Berisi satu atau lebih data bertipe Extension yang digunakan menyimpan informasi apakah obat yang diresepkan atau dikeluarkan merupakan obat non-racikan, obat racikan dengan instruksi berikan dalam dosis demikian/ d.t.d, atau obat racikan non-d.t.d, yang nilai dan strukturnya mengacu pada extension tambahan dengan nama MedicationType.');
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
        Schema::dropIfExists('one_health_medications');
    }
};
