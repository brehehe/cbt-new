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
        Schema::create('medication_request_dosage_instructions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('medication_request_id')->nullable();
            $table->bigInteger('sequence')->comment('Berisi data paket aturan pakai dengan nilai sequence dengan tipe data integer.');
            $table->string('text')->comment('Berisi satu atau lebih data aturan pakai obat dalam bentuk naratif dengan tipe data string.');
            $table->string('additional_text')->nullable()->comment('Berisi data yang berkaitan dengan instruksi tambahan bagi pasien mengenai bagaimana penggunaan obat');
            $table->string('patient_instruction')->nullable()->comment('Berisi data instruksi aturan pakai dengan orientasi pasien dengan tipe data string.');
            $table->bigInteger('timing_repeat_frequency')->comment('Berisi data frekuensi pengulangan dalam jangka waktu (period) tertentu');
            $table->bigInteger('timing_repeat_period')->comment('Berisi data jangka waktu/durasi waktu di mana repetisi akan terjadi');
            $table->string('timing_repeat_period_unit')->comment('Berisi data unit dari period dalam UCUM (http://unitsofmeasure.org)');
            $table->string('route_coding_code')->comment('Berisi data kode untuk aturan kapan suatu obat harus dikonsumsi');
            $table->string('dose_rate_type_coding_code')->comment('Berisi data yang berkaitan dengan jenis atau laju pengobatan yang diresepkan dengan tipe data CodeableConcept yang nilainya mengacu pada data terminologi DoseAndRateType.');
            $table->bigInteger('dose_rate_quantity_value')->comment('Berisi data jumlah obat yang diberikan perdosis dituliskan dalam bentuk kuantitas');
            $table->string('dose_rate_quantity_code')->comment('Berisi data jumlah obat yang diberikan perdosis dituliskan dalam bentuk kuantitas');
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
        Schema::dropIfExists('medication_request_dosage_instructions');
    }
};
