<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * NOTE: PostgreSQL-specific. Skipped on SQLite (e.g. test environment).
     */
    public function up(): void
    {
        if (DB::getDriverName() !== 'pgsql') {
            return;
        }

        DB::statement('ALTER TABLE exam_live_sessions DROP CONSTRAINT IF EXISTS exam_live_sessions_camera_status_check');
        DB::statement("ALTER TABLE exam_live_sessions ADD CONSTRAINT exam_live_sessions_camera_status_check CHECK (camera_status::text = ANY (ARRAY['active'::text, 'inactive'::text, 'error'::text, 'serror'::text, 'pending'::text]))");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() !== 'pgsql') {
            return;
        }

        DB::statement('ALTER TABLE exam_live_sessions DROP CONSTRAINT IF EXISTS exam_live_sessions_camera_status_check');
        DB::statement("ALTER TABLE exam_live_sessions ADD CONSTRAINT exam_live_sessions_camera_status_check CHECK (camera_status::text = ANY (ARRAY['active'::text, 'inactive'::text, 'serror'::text, 'pending'::text]))");
    }
};
