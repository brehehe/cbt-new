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
            // // Timestamp ketika timer dipause akibat force logout
            // $table->timestamp('paused_at')->nullable()->after('end_exam');
            // // Akumulasi total detik yang dipause
            // $table->bigInteger('pause_total_seconds')->default(0)->after('paused_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_timetables', function (Blueprint $table) {
            $table->dropColumn(['paused_at', 'pause_total_seconds']);
        });
    }
};
