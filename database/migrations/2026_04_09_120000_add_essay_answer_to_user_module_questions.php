<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('user_module_questions', function (Blueprint $table) {
            $table->longText('essay_answer')->nullable()->after('timetable_answer_id');
        });

        // Use raw SQL to update enum type in PostgreSQL
        if (DB::getDriverName() === 'pgsql') {
            try {
                DB::statement("ALTER TYPE user_module_questions_status_enum ADD VALUE IF NOT EXISTS 'check'");
            } catch (Exception $e) {
                // Fallback for cases where it's not a native enum or other DBs
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_module_questions', function (Blueprint $table) {
            $table->dropColumn('essay_answer');
        });

        // Removing enum values is not easily supported in PostgreSQL without recreating the type.
        // We'll leave the enum value 'check' there as it doesn't hurt.
    }
};
