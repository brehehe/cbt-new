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
        // 1. Questionnaires
        Schema::create('questionnaires', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('company_id')->nullable();
            $table->string('title');
            $table->string('slug')->unique()->index();
            $table->text('description')->nullable();
            $table->string('academic_year')->nullable();
            $table->string('semester')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('allow_guest')->default(true);
            $table->boolean('require_nim')->default(true);
            $table->foreignUuid('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->softDeletes();
            $table->timestamps();
        });

        // 2. Questionnaire Sections
        Schema::create('questionnaire_sections', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('questionnaire_id')->constrained('questionnaires')->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->integer('order')->default(0);
            $table->timestamps();
        });

        // 3. Questionnaire Questions / Points
        Schema::create('questionnaire_questions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('section_id')->constrained('questionnaire_sections')->cascadeOnDelete();
            $table->text('question_text');
            $table->string('question_type', 30)->default('likert_5'); // 'rating_5', 'likert_5', 'text', 'input_name'
            $table->string('min_label')->nullable();
            $table->string('max_label')->nullable();
            $table->boolean('is_required')->default(true);
            $table->integer('order')->default(0);
            $table->timestamps();
        });

        // 4. Questionnaire Responses
        Schema::create('questionnaire_responses', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('questionnaire_id')->constrained('questionnaires')->cascadeOnDelete();
            $table->foreignUuid('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('respondent_name');
            $table->string('respondent_nim')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamp('submitted_at')->useCurrent();
            $table->timestamps();
        });

        // 5. Questionnaire Answers
        Schema::create('questionnaire_answers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('response_id')->constrained('questionnaire_responses')->cascadeOnDelete();
            $table->foreignUuid('question_id')->constrained('questionnaire_questions')->cascadeOnDelete();
            $table->unsignedTinyInteger('score')->nullable(); // 1-5
            $table->text('text_answer')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questionnaire_answers');
        Schema::dropIfExists('questionnaire_responses');
        Schema::dropIfExists('questionnaire_questions');
        Schema::dropIfExists('questionnaire_sections');
        Schema::dropIfExists('questionnaires');
    }
};
