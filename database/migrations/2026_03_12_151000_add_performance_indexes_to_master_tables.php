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
        // Session & Relationship Tables
        Schema::table('user_module_questions', function (Blueprint $table) {
            $table->index('user_timetable_id');
            $table->index('timetable_module_id');
            $table->index('timetable_question_id');
            $table->index('timetable_answer_id');
            $table->index('study_id');
            $table->index('company_id');
            $table->index('status');
        });

        Schema::table('classmate_students', function (Blueprint $table) {
            $table->index('classmate_id');
            $table->index('user_id');
            $table->index('company_id');
        });

        Schema::table('classmates', function (Blueprint $table) {
            $table->index('user_id');
            $table->index('company_id');
            $table->index('type_study');
        });

        // Master Data Tables
        Schema::table('studies', function (Blueprint $table) {
            $table->index('company_id');
        });

        Schema::table('category_questions', function (Blueprint $table) {
            $table->index('company_id');
            $table->index('name');
        });

        Schema::table('usr_sec_keys', function (Blueprint $table) {
            $table->index('company_id');
            $table->index('user_id');
        });

        Schema::table('exam_rooms', function (Blueprint $table) {
            $table->index('company_id');
            $table->index('is_active');
            $table->index('code');
        });

        Schema::table('exam_sessions', function (Blueprint $table) {
            $table->index('company_id');
            $table->index('is_active');
            $table->index('code');
        });

        Schema::table('material_categories', function (Blueprint $table) {
            $table->index('company_id');
            $table->index('topic_id');
            $table->index('material_category_id');
        });

        Schema::table('materials', function (Blueprint $table) {
            $table->index('company_id');
            $table->index('topic_id');
            $table->index('material_category_id');
            $table->index('level');
        });

        Schema::table('topics', function (Blueprint $table) {
            $table->index('company_id');
            $table->index('study_id');
        });

        Schema::table('rating_scales', function (Blueprint $table) {
            $table->index('company_id');
        });

        Schema::table('exam_types', function (Blueprint $table) {
            $table->index('company_id');
        });

        Schema::table('question_types', function (Blueprint $table) {
            $table->index('company_id');
        });

        Schema::table('regulations', function (Blueprint $table) {
            $table->index('company_id');
            $table->index('type');
        });

        // System & Detail Tables
        Schema::table('company_details', function (Blueprint $table) {
            $table->index('company_id');
        });

        Schema::table('system_settings', function (Blueprint $table) {
            $table->index('company_id');
        });

        Schema::table('services', function (Blueprint $table) {
            $table->index('is_active');
        });

        Schema::table('role_companies', function (Blueprint $table) {
            $table->index('company_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_module_questions', function (Blueprint $table) {
            $table->dropIndex(['user_timetable_id']);
            $table->dropIndex(['timetable_module_id']);
            $table->dropIndex(['timetable_question_id']);
            $table->dropIndex(['timetable_answer_id']);
            $table->dropIndex(['study_id']);
            $table->dropIndex(['company_id']);
            $table->dropIndex(['status']);
        });

        Schema::table('classmate_students', function (Blueprint $table) {
            $table->dropIndex(['classmate_id']);
            $table->dropIndex(['user_id']);
            $table->dropIndex(['company_id']);
        });

        Schema::table('classmates', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
            $table->dropIndex(['company_id']);
            $table->dropIndex(['type_study']);
        });

        Schema::table('studies', function (Blueprint $table) {
            $table->dropIndex(['company_id']);
        });

        Schema::table('category_questions', function (Blueprint $table) {
            $table->dropIndex(['company_id']);
            $table->dropIndex(['name']);
        });

        Schema::table('usr_sec_keys', function (Blueprint $table) {
            $table->dropIndex(['company_id']);
            $table->dropIndex(['user_id']);
        });

        Schema::table('exam_rooms', function (Blueprint $table) {
            $table->dropIndex(['company_id']);
            $table->dropIndex(['is_active']);
            $table->dropIndex(['code']);
        });

        Schema::table('exam_sessions', function (Blueprint $table) {
            $table->dropIndex(['company_id']);
            $table->dropIndex(['is_active']);
            $table->dropIndex(['code']);
        });

        Schema::table('material_categories', function (Blueprint $table) {
            $table->dropIndex(['company_id']);
            $table->dropIndex(['topic_id']);
            $table->dropIndex(['material_category_id']);
        });

        Schema::table('materials', function (Blueprint $table) {
            $table->dropIndex(['company_id']);
            $table->dropIndex(['topic_id']);
            $table->dropIndex(['material_category_id']);
            $table->dropIndex(['level']);
        });

        Schema::table('topics', function (Blueprint $table) {
            $table->dropIndex(['company_id']);
            $table->dropIndex(['study_id']);
        });

        Schema::table('rating_scales', function (Blueprint $table) {
            $table->dropIndex(['company_id']);
        });

        Schema::table('exam_types', function (Blueprint $table) {
            $table->dropIndex(['company_id']);
        });

        Schema::table('question_types', function (Blueprint $table) {
            $table->dropIndex(['company_id']);
        });

        Schema::table('regulations', function (Blueprint $table) {
            $table->dropIndex(['company_id']);
            $table->dropIndex(['type']);
        });

        Schema::table('company_details', function (Blueprint $table) {
            $table->dropIndex(['company_id']);
        });

        Schema::table('system_settings', function (Blueprint $table) {
            $table->dropIndex(['company_id']);
        });

        Schema::table('services', function (Blueprint $table) {
            $table->dropIndex(['is_active']);
        });

        Schema::table('role_companies', function (Blueprint $table) {
            $table->dropIndex(['company_id']);
        });
    }
};
