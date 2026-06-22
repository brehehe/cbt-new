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
        Schema::table('companies', function (Blueprint $table) {
            $table->boolean('import_student_timetable')->default(false)->after('only_admin_generate_token');
        });

        Schema::table('user_details', function (Blueprint $table) {
            $table->foreignUuid('exam_session_id')->nullable()->constrained('exam_sessions')->nullOnDelete();
            $table->foreignUuid('exam_room_id')->nullable()->constrained('exam_rooms')->nullOnDelete();
            $table->date('exam_date')->nullable();
        });

        Schema::table('classmates', function (Blueprint $table) {
            $table->foreignUuid('exam_session_id')->nullable()->constrained('exam_sessions')->nullOnDelete();
            $table->foreignUuid('exam_room_id')->nullable()->constrained('exam_rooms')->nullOnDelete();
            $table->date('exam_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn('import_student_timetable');
        });

        Schema::table('user_details', function (Blueprint $table) {
            $table->dropForeign(['exam_session_id']);
            $table->dropForeign(['exam_room_id']);
            $table->dropColumn(['exam_session_id', 'exam_room_id', 'exam_date']);
        });

        Schema::table('classmates', function (Blueprint $table) {
            $table->dropForeign(['exam_session_id']);
            $table->dropForeign(['exam_room_id']);
            $table->dropColumn(['exam_session_id', 'exam_room_id', 'exam_date']);
        });
    }
};
