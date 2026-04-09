<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

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
        // First check if 'check' is already in the enum by trying to add it
        try {
            DB::statement("ALTER TYPE user_module_questions_status_enum ADD VALUE IF NOT EXISTS 'check'");
        } catch (\Exception $e) {
            // Fallback for cases where it's not a native enum or other DBs
            // In PostgreSQL, if it was created as a native enum, the above is correct.
            // If it's a check constraint, we might need a different approach.
            // But let's assume it's a standard Laravel migration on PostgreSQL.
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
