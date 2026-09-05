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
        if (Schema::hasTable('modules') && ! Schema::hasColumn('modules', 'total_questions')) {
            Schema::table('modules', function (Blueprint $table) {
                $table->integer('total_questions')->nullable()->after('random_question');
            });
        }

        if (Schema::hasTable('timetables') && ! Schema::hasColumn('timetables', 'total_questions')) {
            Schema::table('timetables', function (Blueprint $table) {
                $table->integer('total_questions')->nullable()->after('module_id');
            });
        }

        if (Schema::hasTable('timetable_modules') && ! Schema::hasColumn('timetable_modules', 'total_questions')) {
            Schema::table('timetable_modules', function (Blueprint $table) {
                $table->integer('total_questions')->nullable()->after('random_question');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('modules') && Schema::hasColumn('modules', 'total_questions')) {
            Schema::table('modules', function (Blueprint $table) {
                $table->dropColumn('total_questions');
            });
        }

        if (Schema::hasTable('timetables') && Schema::hasColumn('timetables', 'total_questions')) {
            Schema::table('timetables', function (Blueprint $table) {
                $table->dropColumn('total_questions');
            });
        }

        if (Schema::hasTable('timetable_modules') && Schema::hasColumn('timetable_modules', 'total_questions')) {
            Schema::table('timetable_modules', function (Blueprint $table) {
                $table->dropColumn('total_questions');
            });
        }
    }
};
