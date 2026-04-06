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
        Schema::table('modules', function (Blueprint $table) {
            $table->string('question_pick_type')
                ->default('manual')
                ->after('question_type_id')
                ->comment('tipe pengambilan soal: manual/category/topic');
            $table->jsonb('topic_question_settings')
                ->nullable()
                ->after('category_question_settings')
                ->comment('pengaturan jumlah soal per topik & difficulty');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('modules', function (Blueprint $table) {
            $table->dropColumn('topic_question_settings');
            $table->dropColumn('question_pick_type');
        });
    }
};
