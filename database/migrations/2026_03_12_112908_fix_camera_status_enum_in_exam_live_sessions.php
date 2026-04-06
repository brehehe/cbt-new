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
        // For PostgreSQL, we need to drop the old check constraint and add a new one
        // or change the enum. Since Laravel's enum on PG creates a check constraint,
        // we handle it specifically.
        DB::statement("ALTER TABLE exam_live_sessions DROP CONSTRAINT IF EXISTS exam_live_sessions_camera_status_check");
        DB::statement("ALTER TABLE exam_live_sessions ADD CONSTRAINT exam_live_sessions_camera_status_check CHECK (camera_status::text = ANY (ARRAY['active'::text, 'inactive'::text, 'error'::text, 'serror'::text, 'pending'::text]))");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE exam_live_sessions DROP CONSTRAINT IF EXISTS exam_live_sessions_camera_status_check");
        DB::statement("ALTER TABLE exam_live_sessions ADD CONSTRAINT exam_live_sessions_camera_status_check CHECK (camera_status::text = ANY (ARRAY['active'::text, 'inactive'::text, 'serror'::text, 'pending'::text]))");
    }
};
