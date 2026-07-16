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
            $table->integer('additional_time_seconds')->default(0)->after('pause_total_seconds');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_timetables', function (Blueprint $table) {
            $table->dropColumn('additional_time_seconds');
        });
    }
};
