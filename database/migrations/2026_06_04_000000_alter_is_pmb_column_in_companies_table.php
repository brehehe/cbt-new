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
        Schema::table('companies', function (Blueprint $table) {
            // Using raw statement for pgsql to change boolean to varchar safely
            DB::statement("ALTER TABLE companies ALTER COLUMN is_pmb TYPE VARCHAR(255) USING (CASE WHEN is_pmb THEN 'pmb' ELSE 'non_pmb' END)");
            DB::statement("ALTER TABLE companies ALTER COLUMN is_pmb SET DEFAULT 'non_pmb'");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            // Change it back to boolean
            DB::statement("ALTER TABLE companies ALTER COLUMN is_pmb TYPE BOOLEAN USING (CASE WHEN is_pmb = 'pmb' THEN TRUE ELSE FALSE END)");
            DB::statement("ALTER TABLE companies ALTER COLUMN is_pmb SET DEFAULT FALSE");
        });
    }
};
