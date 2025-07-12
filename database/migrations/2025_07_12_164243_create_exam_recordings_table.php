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
        Schema::create('exam_recordings', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_timetable_id');
            $table->string('video_path')->nullable();
            $table->integer('chunk_number')->default(1);
            $table->bigInteger('file_size')->nullable();
            $table->timestamp('start_time');
            $table->timestamp('end_time')->nullable();
            $table->enum('status', ['recording', 'completed', 'failed'])->default('recording');
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
        Schema::dropIfExists('exam_recordings');
    }
};
