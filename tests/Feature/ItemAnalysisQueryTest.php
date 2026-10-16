<?php

namespace Tests\Feature;

use App\Services\Report\ItemAnalysisService;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Testing\TestCase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ItemAnalysisQueryTest extends TestCase
{
    public function test_bulk_analysis_query_count_does_not_grow_with_questions_or_versions(): void
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('question');
            $table->string('type');
            $table->softDeletes();
        });
        Schema::create('timetable_questions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('question_id');
            $table->string('type');
            $table->integer('order')->default(0);
            $table->softDeletes();
        });
        Schema::create('timetable_answers', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('timetable_question_id');
            $table->string('context');
            $table->string('alphabet');
            $table->boolean('is_correct');
            $table->integer('order')->default(0);
            $table->softDeletes();
        });
        Schema::create('user_module_questions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('timetable_question_id');
            $table->string('timetable_answer_id')->nullable();
            $table->string('user_timetable_id');
            $table->string('status');
            $table->boolean('is_show')->nullable();
            $table->softDeletes();
        });
        $ids = [];
        for ($i = 0; $i < 12; $i++) {
            $ids[] = $id = "q-$i";
            DB::table('questions')->insert(['id' => $id, 'question' => 'Soal', 'type' => 'single']);
            for ($v = 0; $v < 3; $v++) {
                $snapshot = "$id-v$v";
                DB::table('timetable_questions')->insert(['id' => $snapshot, 'question_id' => $id, 'type' => 'single']);
                DB::table('timetable_answers')->insert(['id' => "$snapshot-a", 'timetable_question_id' => $snapshot, 'context' => 'Opsi A', 'alphabet' => 'A', 'is_correct' => true]);
                foreach (['correct', 'unanswered'] as $n => $status) {
                    DB::table('user_module_questions')->insert(['id' => "$snapshot-u$n", 'timetable_question_id' => $snapshot, 'timetable_answer_id' => $n ? null : "$snapshot-a", 'user_timetable_id' => "v$v-u$n", 'status' => $status, 'is_show' => true]);
                }
            }
        }
        $service = new ItemAnalysisService;
        DB::enableQueryLog();
        $single = $service->aggregateMany([$ids[0]]);
        $singleCount = count(DB::getQueryLog());
        DB::flushQueryLog();
        $batch = $service->aggregateMany($ids);
        $batchCount = count(DB::getQueryLog());
        DB::disableQueryLog();
        $this->assertSame(5, $singleCount);
        $this->assertSame($singleCount, $batchCount);
        $this->assertCount(12, $batch);
        $this->assertEquals($single[$ids[0]], $batch[$ids[0]]);
        $this->assertSame(6, $batch[$ids[0]]['total_participants']);
        $this->assertSame(3, $batch[$ids[0]]['correct_answers']);
        $this->assertSame(1.0, $batch[$ids[0]]['discrimination_index']);
        $this->assertSame(6, array_sum(array_column($batch[$ids[0]]['option_analysis'], 'selected_count')));
        $this->assertSame([], $service->aggregateMany([]));
    }
}
