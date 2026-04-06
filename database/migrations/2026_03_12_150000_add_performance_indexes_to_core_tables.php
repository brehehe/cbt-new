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
        // Users & Company Relations
        Schema::table('users', function (Blueprint $table) {
            $table->index('email');
            $table->index('nim');
            $table->index('username');
            $table->index('company_id');
            $table->index('study_id');
        });

        Schema::table('companies', function (Blueprint $table) {
            $table->index('code');
            $table->index('name');
            $table->index('email');
            $table->index('company_id');
            $table->index('service_id');
        });

        Schema::table('user_company_roles', function (Blueprint $table) {
            $table->index('user_id');
            $table->index('role_id');
            $table->index('role_company_id');
            $table->index('company_id');
        });

        // Exam Core Data
        Schema::table('questions', function (Blueprint $table) {
            $table->index('user_id');
            $table->index('study_id');
            $table->index('company_id');
            $table->index('topic_id');
            $table->index('material_category_id');
            $table->index('material_id');
            $table->index('question_type_id');
        });

        Schema::table('answers', function (Blueprint $table) {
            $table->index('company_id');
            $table->index('question_id');
            $table->index('is_correct');
        });

        Schema::table('modules', function (Blueprint $table) {
            $table->index('user_id');
            $table->index('company_id');
            $table->index('question_type_id');
            $table->index('name');
        });

        Schema::table('module_questions', function (Blueprint $table) {
            $table->index('company_id');
            $table->index('module_id');
            $table->index('question_id');
            $table->index('study_id');
        });

        // Timetables & Exam Sessions
        Schema::table('timetables', function (Blueprint $table) {
            $table->index('classmate_id');
            $table->index('module_id');
            $table->index('exam_room_id');
            $table->index('exam_session_id');
            $table->index('study_id');
            $table->index('company_id');
            $table->index('code');
            $table->index('start_time');
            $table->index('end_time');
        });

        Schema::table('user_timetables', function (Blueprint $table) {
            $table->index('user_id');
            $table->index('timetable_id');
            $table->index('study_id');
            $table->index('status');
            $table->index('company_id');
        });

        Schema::table('timetable_modules', function (Blueprint $table) {
            $table->index('timetable_id');
            $table->index('module_id');
            $table->index('user_id');
            $table->index('question_type_id');
            $table->index('company_id');
        });

        Schema::table('timetable_questions', function (Blueprint $table) {
            $table->index('question_id');
            $table->index('timetable_module_id');
            $table->index('user_id');
            $table->index('topic_id');
            $table->index('material_category_id');
            $table->index('material_id');
            $table->index('question_type_id');
            $table->index('study_id');
            $table->index('company_id');
        });

        Schema::table('timetable_answers', function (Blueprint $table) {
            $table->index('answer_id');
            $table->index('timetable_question_id');
            $table->index('company_id');
            $table->index('is_correct');
        });

        // Live Sessions & Surveillance
        Schema::table('exam_live_sessions', function (Blueprint $table) {
            $table->index('company_id');
        });

        Schema::table('exam_alerts', function (Blueprint $table) {
            $table->index('timetable_id');
            $table->index('user_timetable_id');
            $table->index('alert_type');
            $table->index('company_id');
        });

        Schema::table('exam_recordings', function (Blueprint $table) {
            $table->index('timetable_id');
            $table->index('user_timetable_id');
            $table->index('status');
            $table->index('company_id');
            $table->index('start_time');
            $table->index('end_time');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['email']);
            $table->dropIndex(['nim']);
            $table->dropIndex(['username']);
            $table->dropIndex(['company_id']);
            $table->dropIndex(['study_id']);
        });

        Schema::table('companies', function (Blueprint $table) {
            $table->dropIndex(['code']);
            $table->dropIndex(['name']);
            $table->dropIndex(['email']);
            $table->dropIndex(['company_id']);
            $table->dropIndex(['service_id']);
        });

        Schema::table('user_company_roles', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
            $table->dropIndex(['role_id']);
            $table->dropIndex(['role_company_id']);
            $table->dropIndex(['company_id']);
        });

        Schema::table('questions', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
            $table->dropIndex(['study_id']);
            $table->dropIndex(['company_id']);
            $table->dropIndex(['topic_id']);
            $table->dropIndex(['material_category_id']);
            $table->dropIndex(['material_id']);
            $table->dropIndex(['question_type_id']);
        });

        Schema::table('answers', function (Blueprint $table) {
            $table->dropIndex(['company_id']);
            $table->dropIndex(['question_id']);
            $table->dropIndex(['is_correct']);
        });

        Schema::table('modules', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
            $table->dropIndex(['company_id']);
            $table->dropIndex(['question_type_id']);
            $table->dropIndex(['name']);
        });

        Schema::table('module_questions', function (Blueprint $table) {
            $table->dropIndex(['company_id']);
            $table->dropIndex(['module_id']);
            $table->dropIndex(['question_id']);
            $table->dropIndex(['study_id']);
        });

        Schema::table('timetables', function (Blueprint $table) {
            $table->dropIndex(['classmate_id']);
            $table->dropIndex(['module_id']);
            $table->dropIndex(['exam_room_id']);
            $table->dropIndex(['exam_session_id']);
            $table->dropIndex(['study_id']);
            $table->dropIndex(['company_id']);
            $table->dropIndex(['code']);
            $table->dropIndex(['start_time']);
            $table->dropIndex(['end_time']);
        });

        Schema::table('user_timetables', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
            $table->dropIndex(['timetable_id']);
            $table->dropIndex(['study_id']);
            $table->dropIndex(['status']);
            $table->dropIndex(['company_id']);
        });

        Schema::table('timetable_modules', function (Blueprint $table) {
            $table->dropIndex(['timetable_id']);
            $table->dropIndex(['module_id']);
            $table->dropIndex(['user_id']);
            $table->dropIndex(['question_type_id']);
            $table->dropIndex(['company_id']);
        });

        Schema::table('timetable_questions', function (Blueprint $table) {
            $table->dropIndex(['question_id']);
            $table->dropIndex(['timetable_module_id']);
            $table->dropIndex(['user_id']);
            $table->dropIndex(['topic_id']);
            $table->dropIndex(['material_category_id']);
            $table->dropIndex(['material_id']);
            $table->dropIndex(['question_type_id']);
            $table->dropIndex(['study_id']);
            $table->dropIndex(['company_id']);
        });

        Schema::table('timetable_answers', function (Blueprint $table) {
            $table->dropIndex(['answer_id']);
            $table->dropIndex(['timetable_question_id']);
            $table->dropIndex(['company_id']);
            $table->dropIndex(['is_correct']);
        });

        Schema::table('exam_live_sessions', function (Blueprint $table) {
            $table->dropIndex(['company_id']);
        });

        Schema::table('exam_alerts', function (Blueprint $table) {
            $table->dropIndex(['timetable_id']);
            $table->dropIndex(['user_timetable_id']);
            $table->dropIndex(['alert_type']);
            $table->dropIndex(['company_id']);
        });

        Schema::table('exam_recordings', function (Blueprint $table) {
            $table->dropIndex(['timetable_id']);
            $table->dropIndex(['user_timetable_id']);
            $table->dropIndex(['status']);
            $table->dropIndex(['company_id']);
            $table->dropIndex(['start_time']);
            $table->dropIndex(['end_time']);
        });
    }
};
