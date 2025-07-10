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
        Schema::create('modules', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('company_id')->nullable();
            $table->foreignUuid('question_type_id')->nullable();
            $table->string('name')->comment('Nama modul soal');
            $table->bigInteger('duration')->default(0)->comment('durasi waktu pengerjaan');
            $table->longText('description')->nullable()->comment('keterangan modul');
            $table->boolean('random_question')->default(false)->comment('apakah soal dalam modul diacak atau urut');
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
        Schema::dropIfExists('modules');
    }
};
