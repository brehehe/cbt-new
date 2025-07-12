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
        Schema::create('timetable_questions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('question_id');
            $table->foreignUuid('timetable_module_id');
            $table->foreignUuid('user_id')->nullable();
            $table->foreignUuid('topic_id')->nullable();
            $table->foreignUuid('material_category_id')->nullable();
            $table->foreignUuid('material_id')->nullable();
            $table->foreignUuid('question_type_id')->nullable();
            $table->longText('question')->comment('soal');
            $table->jsonb('images')->nullable()->comment('gambar soal');
            $table->longText('description')->nullable()->comment('keterangan soal');
            $table->double('weight_correct')->nullable()->comment('score jika soal ini terjawab benar');
            $table->double('weight_incorrect')->nullable()->comment('score jika soal ini terjawab salah');
            $table->foreignUuid('company_id')->nullable();
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
        Schema::dropIfExists('timetable_questions');
    }
};
