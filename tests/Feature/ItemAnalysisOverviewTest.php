<?php

namespace Tests\Feature;

use App\Services\Report\ItemAnalysisService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Events\Dispatcher;
use Illuminate\Foundation\Testing\TestCase;

class ItemAnalysisOverviewTest extends TestCase
{
    public function test_shared_overview_renders_as_html_and_downloadable_pdf(): void
    {
        // Isolate report rendering from the application-wide company view composer.
        app('view')->setDispatcher(new Dispatcher(app()));
        $question = (object) ['question' => 'Contoh soal analisis', 'type' => 'single'];
        $analysis = (new ItemAnalysisService)->getEmptyAnalysis($question);
        $data = ['title' => 'Overview Ujian', 'analyses' => [$analysis]];
        $html = view('livewire.admin.report.shared.overview-pdf', $data)->render();
        $this->assertStringContainsString('Contoh soal analisis', $html);
        $this->assertStringContainsString('Analisis Kelompok', $html);
        $this->assertStringContainsString('Kontribusi Reliabilitas', $html);
        $pdf = Pdf::loadView('livewire.admin.report.shared.overview-pdf', $data)->setPaper('a4', 'portrait')->output();
        $this->assertStringStartsWith('%PDF-', $pdf);
    }
}
