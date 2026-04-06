<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('category_questions')) {
            DB::statement('ALTER TABLE category_questions ALTER COLUMN name TYPE text');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('category_questions')) {
            DB::statement('ALTER TABLE category_questions ALTER COLUMN name TYPE varchar(255)');
        }
    }
};
