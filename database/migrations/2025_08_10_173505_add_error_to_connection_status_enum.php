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
        // For PostgreSQL, we need to use raw SQL to modify enum constraint
        DB::statement("ALTER TABLE exam_live_sessions DROP CONSTRAINT IF EXISTS exam_live_sessions_connection_status_check");
        DB::statement("ALTER TABLE exam_live_sessions ADD CONSTRAINT exam_live_sessions_connection_status_check CHECK (connection_status IN ('connected', 'disconnected', 'unstable', 'error', 'streaming'))");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to original constraint
        DB::statement("ALTER TABLE exam_live_sessions DROP CONSTRAINT IF EXISTS exam_live_sessions_connection_status_check");
        DB::statement("ALTER TABLE exam_live_sessions ADD CONSTRAINT exam_live_sessions_connection_status_check CHECK (connection_status IN ('connected', 'disconnected', 'unstable'))");
    }
};
