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
        Schema::create('user_module_questions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_timetable_id');
            $table->foreignUuid('module_question_id')->nullable();
            $table->foreignUuid('answer_id')->nullable();
            $table->enum('status', ['answer', 'mark', 'not'])->default('not');
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
        Schema::dropIfExists('user_module_questions');
    }
};
