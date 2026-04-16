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
        Schema::table('modules', function (Blueprint $table) {
            if (!Schema::hasColumn('modules', 'is_simulation')) {
                $table->enum('is_simulation', ['true', 'false'])->default('false')->index();
            }
        });

        Schema::table('questions', function (Blueprint $table) {
            if (!Schema::hasColumn('questions', 'is_simulation')) {
                $table->enum('is_simulation', ['true', 'false'])->default('false')->index();
            }
        });

        Schema::table('timetables', function (Blueprint $table) {
            if (!Schema::hasColumn('timetables', 'is_simulation')) {
                $table->enum('is_simulation', ['true', 'false'])->default('false')->index();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('modules', function (Blueprint $table) {
            $table->dropColumn('is_simulation');
        });
        
        Schema::table('questions', function (Blueprint $table) {
            $table->dropColumn('is_simulation');
        });

        Schema::table('timetables', function (Blueprint $table) {
            $table->dropColumn('is_simulation');
        });
    }
};
