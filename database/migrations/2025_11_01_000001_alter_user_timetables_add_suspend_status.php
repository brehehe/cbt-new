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
        $driver = DB::getDriverName();
        if ($driver === 'sqlite') {
            return; // SQLite tidak mendukung ALTER COLUMN TYPE
        }
        if ($driver === 'mysql') {
            DB::statement("ALTER TABLE user_timetables MODIFY COLUMN status ENUM('warning','exam','done','suspend') DEFAULT 'warning'");
        } elseif ($driver === 'pgsql') {
            DB::statement('ALTER TABLE user_timetables ALTER COLUMN status TYPE VARCHAR(20)');
        }
    }

    public function down(): void
    {
        $driver = DB::getDriverName();
        if ($driver === 'sqlite') {
            return;
        }
        if ($driver === 'mysql') {
            DB::statement("ALTER TABLE user_timetables MODIFY COLUMN status ENUM('warning','exam','done') DEFAULT 'warning'");
        }
    }
};
