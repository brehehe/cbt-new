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
        Schema::table('timetable_questions', function (Blueprint $table) {
            $table->unique(['timetable_module_id', 'question_id'], 'timetable_questions_module_question_unique');
        });

        Schema::table('timetable_answers', function (Blueprint $table) {
            $table->unique(['timetable_question_id', 'answer_id'], 'timetable_answers_question_answer_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('timetable_answers', function (Blueprint $table) {
            $table->dropUnique('timetable_answers_question_answer_unique');
        });

        Schema::table('timetable_questions', function (Blueprint $table) {
            $table->dropUnique('timetable_questions_module_question_unique');
        });
    }
};
