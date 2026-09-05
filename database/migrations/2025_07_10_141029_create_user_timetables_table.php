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
        Schema::create('user_timetables', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id');
            $table->foreignUuid('timetable_id');
            $table->dateTime('start_process');
            $table->dateTime('start_exam')->nullable();
            $table->dateTime('end_exam')->nullable();
            // Pause support: when force logout happens
            $table->timestamp('paused_at')->nullable();
            $table->bigInteger('pause_total_seconds')->default(0);
            $table->decimal('mark', 15, 2)->default(0);
            $table->foreignUuid('study_id')->nullable();
            $table->enum('status', ['warning', 'exam', 'done', 'suspend'])->default('warning');
            $table->foreignUuid('company_id')->nullable();
            $table->bigInteger('order')->default(0);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_timetables');
    }
};
