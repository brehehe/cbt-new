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
        Schema::table('module_questions', function (Blueprint $table) {
            $table->string('question_pick_type')->default('manual')->nullable()->after('study_id');
            $table->index('question_pick_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('module_questions', function (Blueprint $table) {
            $table->dropIndex(['question_pick_type']);
            $table->dropColumn('question_pick_type');
        });
    }
};
