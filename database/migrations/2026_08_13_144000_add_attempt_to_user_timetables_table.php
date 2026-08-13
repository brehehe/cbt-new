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
        Schema::table('user_timetables', function (Blueprint $table) {
            if (! Schema::hasColumn('user_timetables', 'attempt')) {
                $table->integer('attempt')->default(1)->after('status');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_timetables', function (Blueprint $table) {
            if (Schema::hasColumn('user_timetables', 'attempt')) {
                $table->dropColumn('attempt');
            }
        });
    }
};
