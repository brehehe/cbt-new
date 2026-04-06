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
            $table->longText('latex')->nullable()->comment('LaTeX content for question');
            $table->string('latex_preview_pdf')->nullable()->comment('LaTeX preview PDF path');
            $table->string('latex_preview_png')->nullable()->comment('LaTeX preview PNG path');
        });

        Schema::table('timetable_answers', function (Blueprint $table) {
            $table->longText('latex')->nullable()->comment('LaTeX content for answer');
            $table->string('latex_preview_pdf')->nullable()->comment('LaTeX preview PDF path');
            $table->string('latex_preview_png')->nullable()->comment('LaTeX preview PNG path');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('timetable_questions', function (Blueprint $table) {
            $table->dropColumn(['latex', 'latex_preview_pdf', 'latex_preview_png']);
        });

        Schema::table('timetable_answers', function (Blueprint $table) {
            $table->dropColumn(['latex', 'latex_preview_pdf', 'latex_preview_png']);
        });
    }
};
