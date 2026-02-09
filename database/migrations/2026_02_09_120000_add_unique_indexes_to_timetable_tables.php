<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement('CREATE UNIQUE INDEX IF NOT EXISTS timetable_questions_module_question_unique ON timetable_questions (timetable_module_id, question_id)');
        DB::statement('CREATE UNIQUE INDEX IF NOT EXISTS timetable_answers_question_answer_unique ON timetable_answers (timetable_question_id, answer_id)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('DROP INDEX IF EXISTS timetable_answers_question_answer_unique');
        DB::statement('DROP INDEX IF EXISTS timetable_questions_module_question_unique');
    }
};
