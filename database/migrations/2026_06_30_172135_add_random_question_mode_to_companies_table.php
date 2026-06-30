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
            // Mode acak soal: 'topic_grouped' = acak dalam topik (topik tetap urut), 'fully_random' = acak total
            $table->string('random_question_mode')->default('topic_grouped')->after('import_student_timetable');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn('random_question_mode');
        });
    }
};
