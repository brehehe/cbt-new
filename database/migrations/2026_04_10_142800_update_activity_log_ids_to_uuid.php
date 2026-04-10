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
        $tableName = config('activitylog.table_name');

        // Check the database driver
        $driver = Schema::getConnection()->getDriverName();

        if ($driver === 'pgsql') {
            // For PostgreSQL, we need to use a raw statement to handle the type casting
            DB::statement("ALTER TABLE {$tableName} ALTER COLUMN subject_id TYPE UUID USING subject_id::text::UUID");
            DB::statement("ALTER TABLE {$tableName} ALTER COLUMN causer_id TYPE UUID USING causer_id::text::UUID");
        } else {
            // For other drivers (MySQL, SQLite)
            Schema::table($tableName, function (Blueprint $table) {
                $table->uuid('subject_id')->nullable()->change();
                $table->uuid('causer_id')->nullable()->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tableName = config('activitylog.table_name');
        
        $driver = Schema::getConnection()->getDriverName();

        if ($driver === 'pgsql') {
            DB::statement("ALTER TABLE {$tableName} ALTER COLUMN subject_id TYPE BIGINT USING subject_id::text::BIGINT");
            DB::statement("ALTER TABLE {$tableName} ALTER COLUMN causer_id TYPE BIGINT USING causer_id::text::BIGINT");
        } else {
            Schema::table($tableName, function (Blueprint $table) {
                $table->bigInteger('subject_id')->nullable()->change();
                $table->bigInteger('causer_id')->nullable()->change();
            });
        }
    }
};
