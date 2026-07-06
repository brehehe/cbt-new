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
        if (DB::getDriverName() === 'pgsql') {
            DB::statement("ALTER TABLE companies ALTER COLUMN is_pmb TYPE VARCHAR(255) USING (CASE WHEN is_pmb THEN 'pmb' ELSE 'non_pmb' END)");
            DB::statement("ALTER TABLE companies ALTER COLUMN is_pmb SET DEFAULT 'non_pmb'");
        } elseif (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE companies MODIFY COLUMN is_pmb VARCHAR(255) DEFAULT 'non_pmb'");
            DB::statement("UPDATE companies SET is_pmb = CASE WHEN is_pmb = '1' OR is_pmb = 'true' THEN 'pmb' ELSE 'non_pmb' END");
        } else {
            Schema::table('companies', function (Blueprint $table) {
                $table->string('is_pmb', 255)->default('non_pmb')->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() === 'pgsql') {
            DB::statement("ALTER TABLE companies ALTER COLUMN is_pmb TYPE BOOLEAN USING (CASE WHEN is_pmb = 'pmb' THEN TRUE ELSE FALSE END)");
            DB::statement("ALTER TABLE companies ALTER COLUMN is_pmb SET DEFAULT FALSE");
        } elseif (DB::getDriverName() === 'mysql') {
            DB::statement("UPDATE companies SET is_pmb = CASE WHEN is_pmb = 'pmb' THEN 1 ELSE 0 END");
            DB::statement("ALTER TABLE companies MODIFY COLUMN is_pmb TINYINT(1) NOT NULL DEFAULT 0");
        } else {
            Schema::table('companies', function (Blueprint $table) {
                $table->boolean('is_pmb')->default(false)->change();
            });
        }
    }
};
