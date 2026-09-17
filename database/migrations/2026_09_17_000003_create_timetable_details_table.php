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
        Schema::create('timetable_details', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('timetable_id')->constrained('timetables')->cascadeOnDelete();
            $table->string('code')->unique()->comment('ID QRCODE Detail');
            $table->foreignUuid('exam_room_id')->nullable()->constrained('exam_rooms')->nullOnDelete();
            $table->foreignUuid('exam_session_id')->nullable()->constrained('exam_sessions')->nullOnDelete();
            $table->jsonb('supervisors')->nullable();
            $table->date('exam_date')->nullable();
            $table->dateTime('start_time')->nullable();
            $table->dateTime('end_time')->nullable();
            $table->string('type')->default('exam')->comment('exam, material');
            
            // Exam Specific Fields
            $table->foreignUuid('module_id')->nullable()->constrained('modules')->nullOnDelete();
            $table->string('exam_type')->nullable();
            $table->boolean('allow_repeat')->default(false);
            $table->boolean('require_token')->default(true);
            $table->string('token')->nullable();
            $table->boolean('is_camera')->default(false);
            $table->boolean('is_recording')->default(false);
            $table->boolean('is_streaming')->default(false);

            // Material Specific Fields
            $table->foreignUuid('digital_book_id')->nullable()->constrained('digital_books')->nullOnDelete();

            // Attendance Required Toggle
            $table->boolean('require_attendance')->default(false);

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
        Schema::dropIfExists('timetable_details');
    }
};
