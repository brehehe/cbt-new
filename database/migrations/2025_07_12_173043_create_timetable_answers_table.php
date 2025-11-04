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
        Schema::create('timetable_answers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('answer_id');
            $table->foreignUuid('timetable_question_id');
            $table->string('alphabet')->nullable();
            $table->text('context')->nullable();
            $table->jsonb('images')->nullable();
            $table->boolean('is_correct')->default(0);
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
        Schema::dropIfExists('timetable_answers');
    }
};
