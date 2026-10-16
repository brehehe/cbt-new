<?php

namespace Tests\Unit;

use App\Services\Report\ItemAnalysisService;
use PHPUnit\Framework\TestCase;

class ItemAnalysisServiceTest extends TestCase
{
    public function test_reference_example_and_unanswered_distribution(): void
    {
        $service = new ItemAnalysisService;
        $question = (object) ['type' => 'single', 'answers' => collect([
            (object) ['id' => 'a', 'is_correct' => false],
            (object) ['id' => 'b', 'is_correct' => true],
        ])];
        $scores = [];
        $rows = collect();
        for ($i = 0; $i < 51; $i++) {
            $correct = $i < 3 || ($i >= 14 && $i < 27) || ($i >= 37 && $i < 42);
            $scores[$i] = ['score' => 51 - $i];
            $rows->push((object) ['user_timetable_id' => $i, 'status' => $correct ? 'correct' : ($i === 50 ? 'unanswered' : 'wrong'), 'timetable_answer_id' => $i === 50 ? null : ($correct ? 'b' : 'a')]);
        }
        $result = $service->analyzeItem($question, $rows, $scores);
        $this->assertSame(0.412, $result['difficulty_index']);
        $this->assertSame(-0.143, $result['discrimination_index']);
        $this->assertSame(-0.0346, $result['reliability_contribution']);
        $this->assertSame(14, $result['upper_group_total']);
        $this->assertSame(3, $result['upper_group_correct']);
        $this->assertSame(5, $result['lower_group_correct']);
        $this->assertSame(51, array_sum(array_column($result['option_analysis'], 'selected_count')));
        $this->assertSame(1, $result['option_analysis'][2]['selected_count']);
    }

    public function test_single_participant_cannot_form_overlapping_groups(): void
    {
        $result = (new ItemAnalysisService)->calculateDiscrimination(null, [1 => ['score' => 1]], collect([(object) ['user_timetable_id' => 1, 'status' => 'correct']]));
        $this->assertSame(0, $result['upper_group_total']);
        $this->assertStringContainsString('Tidak dapat dihitung', $result['discrimination_level']);
    }
}
