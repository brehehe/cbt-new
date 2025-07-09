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
        Schema::create('medication_ingredients', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('medication_id')->nullable();
            $table->foreignUuid('product_id')->nullable();
            $table->string('item_coding_code')->comment('Berisi data kode zat aktif atau kode obat template dengan tipe data Coding, yang nilainya mengacu pada data terminologi [Kamus Farmasi dan Alat Kesehatan.');
            $table->string('item_coding_display')->nullable()->comment('Berisi data kode zat aktif atau kode obat template dengan tipe data Coding, yang nilainya mengacu pada data terminologi [Kamus Farmasi dan Alat Kesehatan.');
            $table->boolean('is_active')->default(true)->comment('Berisi data informasi apakah komposisi obat tersebut merupakan zat aktif dengan tipe data boolean.');
            $table->decimal('strength_numerator_value', 15);
            $table->string('strength_numerator_code')->comment('Berisi dari data master_value_quantities');
            $table->decimal('strength_denominator_value', 15);
            $table->string('strength_denominator_code')->comment('Berisi dari data master_value_quantities, master_orderable_drug_forms');
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
        Schema::dropIfExists('medication_ingredients');
    }
};
