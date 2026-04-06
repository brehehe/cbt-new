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
        Schema::table('timetable_questions', function (Blueprint $table) {
            //
            $table->boolean('is_check')->default(false)->after('order')->comment('menandai soal sudah dicek atau belum');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('timetable_questions', function (Blueprint $table) {
            //
        });
    }
};
