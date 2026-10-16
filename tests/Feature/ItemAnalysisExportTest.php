<?php

namespace Tests\Feature;

use App\Livewire\Admin\Report\ItemAnalysis\AdminReportItemAnalysisAllIndex;
use App\Services\Report\ItemAnalysisService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Foundation\Testing\TestCase;
use Illuminate\Support\Facades\DB;

class ItemAnalysisExportTest extends TestCase
{
    public function test_export_uses_the_question_id_alias_from_the_overview_query(): void
    {
        $component = new class extends AdminReportItemAnalysisAllIndex
        {
            protected function itemsQuery()
            {
                return DB::query()->selectRaw("'question-123' as question_id");
            }
        };
        $this->mock(ItemAnalysisService::class, function ($mock) {
            $mock->shouldReceive('aggregateMany')->once()->with(['question-123'])->andReturn(['question-123' => ['total_participants' => 5]]);
        });
        Pdf::shouldReceive('loadView')->once()->with('livewire.admin.report.shared.overview-pdf', [
            'title' => 'Analisis Butir Soal — Semua Jadwal',
            'analyses' => ['question-123' => ['total_participants' => 5]],
        ])->andReturnSelf();
        Pdf::shouldReceive('setPaper')->once()->with('a4', 'portrait')->andReturnSelf();
        Pdf::shouldReceive('output')->once()->andReturn('%PDF-test');

        $response = $component->exportOverview();
        ob_start();
        $response->sendContent();
        $content = ob_get_clean();
        $this->assertSame('%PDF-test', $content);
        $this->assertSame(200, $response->getStatusCode());
    }
}
