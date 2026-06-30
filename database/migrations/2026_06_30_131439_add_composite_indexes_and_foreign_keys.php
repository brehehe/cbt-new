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
        // 1. user_timetables
        Schema::table('user_timetables', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('timetable_id')->references('id')->on('timetables')->onDelete('cascade');
            
            $table->index(['timetable_id', 'user_id'], 'ut_tt_user_composite_index');
            $table->index(['timetable_id', 'status'], 'ut_tt_status_composite_index');
        });

        // 2. user_module_questions
        Schema::table('user_module_questions', function (Blueprint $table) {
            $table->foreign('user_timetable_id')->references('id')->on('user_timetables')->onDelete('cascade');
            $table->foreign('timetable_question_id')->references('id')->on('timetable_questions')->onDelete('cascade');
            
            $table->index(['user_timetable_id', 'timetable_question_id'], 'umq_ut_tq_composite_index');
            $table->index(['user_timetable_id', 'status'], 'umq_ut_status_composite_index');
        });

        // 3. timetable_questions
        Schema::table('timetable_questions', function (Blueprint $table) {
            $table->foreign('timetable_module_id')->references('id')->on('timetable_modules')->onDelete('cascade');
            
            $table->index(['timetable_module_id', 'is_check'], 'tq_tm_is_check_composite_index');
        });

        // 4. classmate_students
        Schema::table('classmate_students', function (Blueprint $table) {
            $table->foreign('classmate_id')->references('id')->on('classmates')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            
            $table->index(['classmate_id', 'user_id'], 'cs_classmate_user_composite_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // 1. classmate_students
        Schema::table('classmate_students', function (Blueprint $table) {
            $table->dropForeign(['classmate_id']);
            $table->dropForeign(['user_id']);
            
            $table->dropIndex('cs_classmate_user_composite_index');
        });

        // 2. timetable_questions
        Schema::table('timetable_questions', function (Blueprint $table) {
            $table->dropForeign(['timetable_module_id']);
            
            $table->dropIndex('tq_tm_is_check_composite_index');
        });

        // 3. user_module_questions
        Schema::table('user_module_questions', function (Blueprint $table) {
            $table->dropForeign(['user_timetable_id']);
            $table->dropForeign(['timetable_question_id']);
            
            $table->dropIndex('umq_ut_tq_composite_index');
            $table->dropIndex('umq_ut_status_composite_index');
        });

        // 4. user_timetables
        Schema::table('user_timetables', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['timetable_id']);
            
            $table->dropIndex('ut_tt_user_composite_index');
            $table->dropIndex('ut_tt_status_composite_index');
        });
    }
};
