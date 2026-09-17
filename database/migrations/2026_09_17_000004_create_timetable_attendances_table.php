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
        Schema::create('timetable_attendances', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('timetable_id')->constrained('timetables')->cascadeOnDelete();
            $table->foreignUuid('timetable_detail_id')->nullable()->constrained('timetable_details')->cascadeOnDelete();
            $table->foreignUuid('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignUuid('scanned_by')->nullable()->constrained('users')->nullOnDelete();
            $table->dateTime('attended_at');
            $table->string('status')->default('present')->comment('present, late, absent');
            $table->string('method')->default('camera_scan')->comment('camera_scan, manual, student_scan');
            $table->text('notes')->nullable();
            $table->foreignUuid('company_id')->nullable();
            $table->timestamps();

            $table->unique(['timetable_detail_id', 'user_id'], 'tt_att_detail_user_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('timetable_attendances');
    }
};
