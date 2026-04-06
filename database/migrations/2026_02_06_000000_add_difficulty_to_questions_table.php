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
        Schema::table('questions', function (Blueprint $table) {
            if (!Schema::hasColumn('questions', 'difficulty')) {
                $table->enum('difficulty', ['default', 'easy', 'medium', 'hard'])
                    ->default('default')
                    ->after('question_type_id')
                    ->comment('tingkat kesulitan soal');
            }
            if (!Schema::hasColumn('questions', 'category_question_id')) {
                $table->foreignUuid('category_question_id')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->dropColumn('difficulty');
        });
    }
};
