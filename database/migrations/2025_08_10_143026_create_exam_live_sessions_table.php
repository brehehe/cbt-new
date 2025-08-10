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
        Schema::create('exam_live_sessions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('timetable_id')->nullable();
            $table->foreignUuid('user_timetable_id');
            $table->foreignUuid('user_id');
            $table->foreignUuid('company_id')->nullable();
            $table->string('session_token')->unique();
            $table->string('camera_stream_url')->nullable();
            $table->string('screen_stream_url')->nullable();
            $table->integer('current_question_number')->default(1);
            $table->integer('total_questions')->default(0);
            $table->integer('answered_questions')->default(0);
            $table->integer('marked_questions')->default(0);
            $table->enum('camera_status', ['active', 'inactive', 'error', 'pending'])->default('pending');
            $table->enum('screen_status', ['active', 'inactive', 'error', 'pending'])->default('pending');
            $table->enum('connection_status', ['connected', 'disconnected', 'unstable'])->default('connected');
            $table->timestamp('last_activity')->nullable();
            $table->integer('warning_count')->default(0);
            $table->integer('alert_count')->default(0);
            $table->boolean('is_active')->default(true);
            $table->json('session_metadata')->nullable();
            $table->json('browser_info')->nullable();
            $table->json('device_info')->nullable();
            $table->json('location_info')->nullable();
            $table->bigInteger('order')->default(0);
            $table->softDeletes();
            $table->timestamps();

            // Indexes
            $table->index(['timetable_id', 'is_active']);
            $table->index(['user_timetable_id']);
            $table->index(['user_id', 'is_active']);
            $table->index(['last_activity']);
            $table->index(['connection_status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exam_live_sessions');
    }
};
