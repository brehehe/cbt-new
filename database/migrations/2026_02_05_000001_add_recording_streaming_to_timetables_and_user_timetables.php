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
        Schema::table('timetables', function (Blueprint $table) {
            $table->boolean('is_recording')->default(false)->after('order');
            $table->boolean('is_streaming')->default(false)->after('is_recording');
        });

        Schema::table('user_timetables', function (Blueprint $table) {
            $table->boolean('is_recording')->default(false)->after('order');
            $table->boolean('is_streaming')->default(false)->after('is_recording');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('timetables', function (Blueprint $table) {
            $table->dropColumn(['is_recording', 'is_streaming']);
        });

        Schema::table('user_timetables', function (Blueprint $table) {
            $table->dropColumn(['is_recording', 'is_streaming']);
        });
    }
};
