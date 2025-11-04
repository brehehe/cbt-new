<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Expand status to include 'suspend'.
        // MySQL: modify enum; PostgreSQL: relax to VARCHAR to allow new value.
        $driver = DB::getDriverName();
        if ($driver === 'mysql') {
            DB::statement("ALTER TABLE user_timetables MODIFY COLUMN status ENUM('warning','exam','done','suspend') DEFAULT 'warning'");
        } else if ($driver === 'pgsql') {
            // Convert to VARCHAR to allow 'suspend' without complex enum fix
            DB::statement("ALTER TABLE user_timetables ALTER COLUMN status TYPE VARCHAR(20)");
        } else {
            // Fallback: attempt generic alteration to VARCHAR
            DB::statement("ALTER TABLE user_timetables ALTER COLUMN status TYPE VARCHAR(20)");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $driver = DB::getDriverName();
        if ($driver === 'mysql') {
            DB::statement("ALTER TABLE user_timetables MODIFY COLUMN status ENUM('warning','exam','done') DEFAULT 'warning'");
        } else if ($driver === 'pgsql') {
            // Keep as VARCHAR since reverting to a check constraint is environment-specific
        }
    }
};