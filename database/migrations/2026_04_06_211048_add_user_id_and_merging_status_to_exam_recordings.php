<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('exam_recordings', function (Blueprint $table) {
            // Add user_id to track who owns the recording
            $table->foreignUuid('user_id')->nullable()->after('user_timetable_id');
        });

        // Extend the status enum to include 'merging' using raw SQL (PostgreSQL only)
        if (DB::getDriverName() === 'pgsql') {
            DB::statement('ALTER TABLE exam_recordings DROP CONSTRAINT IF EXISTS exam_recordings_status_check');
            DB::statement("ALTER TABLE exam_recordings ADD CONSTRAINT exam_recordings_status_check CHECK (status IN ('recording', 'merging', 'completed', 'failed'))");
        }
    }

    public function down(): void
    {
        Schema::table('exam_recordings', function (Blueprint $table) {
            $table->dropColumn('user_id');
        });

        if (DB::getDriverName() === 'pgsql') {
            DB::statement('ALTER TABLE exam_recordings DROP CONSTRAINT IF EXISTS exam_recordings_status_check');
            DB::statement("ALTER TABLE exam_recordings ADD CONSTRAINT exam_recordings_status_check CHECK (status IN ('recording', 'completed', 'failed'))");
        }
    }
};
