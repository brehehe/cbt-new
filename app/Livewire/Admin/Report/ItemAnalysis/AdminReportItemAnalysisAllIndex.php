<?php

namespace App\Livewire\Admin\Report\ItemAnalysis;

use App\Helpers\AlertHelper;
use App\Models\Master\Question\Question;
use App\Models\Timetable\TimetableQuestion;
use App\Models\User\UserModuleQuestion;
use App\Services\Report\ItemAnalysisService;
use App\Traits\HasQuestionReportFilters;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class AdminReportItemAnalysisAllIndex extends Component
{
    use HasQuestionReportFilters, WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $perPage = 10;

    public $search = '';

    public $selectedQuestionId = null;

    public function showDetail(string $id): void
    {
        $this->selectedQuestionId = $id;
    }

    public function closeDetail(): void
    {
        $this->selectedQuestionId = null;
    }

    public function exportOverview()
    {
        @ini_set('memory_limit', '512M');
        @set_time_limit(300);

        $service = app(ItemAnalysisService::class);
        // The grouped query exposes questions.id as question_id.
        $ids = $this->selectedQuestionId ? [$this->selectedQuestionId] : $this->itemsQuery()->get()->pluck('question_id')->all();
        $analyses = $service->aggregateMany($ids);
        $this->skipRender();
        $pdf = Pdf::loadView('livewire.admin.report.shared.overview-pdf', [
            'title' => 'Analisis Butir Soal — Semua Jadwal', 'analyses' => $analyses,
        ])->setPaper('a4', 'portrait');

        return response()->streamDownload(fn () => print ($pdf->output()), 'overview-analisis-semua-soal.pdf');
    }

    public $difficultySelections = [];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updateDifficulty(string $questionId): void
    {
        $difficulty = $this->difficultySelections[$questionId] ?? 'default';
        $allowed = ['default', 'easy', 'medium', 'hard'];

        if (! in_array($difficulty, $allowed, true)) {
            $difficulty = 'default';
        }

        DB::transaction(function () use ($questionId, $difficulty) {
            Question::where('id', $questionId)->update([
                'difficulty' => $difficulty,
            ]);

            TimetableQuestion::where('question_id', $questionId)->update([
                'difficulty' => $difficulty,
            ]);
        });

        session()->flash('message', 'Difficulty berhasil diperbarui.');
    }

    /**
     * Konfirmasi sebelum menyembunyikan riwayat pengerjaan dari item analisis.
     * Riwayat ujian siswa TETAP tersimpan, hanya disembunyikan dari perhitungan analisis.
     */
    public function confirmDeleteAttempts(string $questionId): void
    {
        AlertHelper::confirmDelete(
            'deleteAttempts',
            'Hapus riwayat pengerjaan soal ini? ',
            $questionId
        );
    }

    /**
     * Sembunyikan riwayat pengerjaan dari item analisis dengan is_show = false.
     * History ujian siswa TIDAK dihapus — tetap tampil di halaman detail ujian siswa.
     */
    public function deleteAttempts($params): void
    {
        $questionId = is_array($params) ? $params[0] : $params;

        DB::transaction(function () use ($questionId) {
            $timetableQuestionIds = TimetableQuestion::withoutGlobalScope('user_scope')
                ->where('question_id', $questionId)
                ->pluck('id');

            // Set is_show = false: data tetap ada, tidak ikut dihitung di item analisis.
            // History ujian siswa masih terlihat di halaman riwayat detail.
            $companyId = auth()->user()?->company_id;
            UserModuleQuestion::withoutGlobalScope('user_scope')
                ->whereIn('timetable_question_id', $timetableQuestionIds)
                ->when($companyId, fn ($q) => $q->where('company_id', $companyId))
                ->update(['is_show' => false]);
        });

        AlertHelper::success('Berhasil', 'Riwayat pengerjaan soal ini disembunyikan dari Item Analisis. History ujian siswa tetap utuh.');
    }

    /**
     * Tampilkan kembali riwayat pengerjaan ke item analisis (is_show = true).
     */
    public function restoreAttempts(string $questionId): void
    {
        DB::transaction(function () use ($questionId) {
            $timetableQuestionIds = TimetableQuestion::withoutGlobalScope('user_scope')
                ->where('question_id', $questionId)
                ->pluck('id');

            $companyId = auth()->user()?->company_id;
            UserModuleQuestion::withoutGlobalScope('user_scope')
                ->whereIn('timetable_question_id', $timetableQuestionIds)
                ->when($companyId, fn ($q) => $q->where('company_id', $companyId))
                ->update(['is_show' => true]);
        });

        AlertHelper::success('Berhasil', 'Riwayat pengerjaan soal ini ditampilkan kembali di Item Analisis.');
    }

    public function generateAll(): void
    {
        $companyId = auth()->user()?->company_id;

        $rows = UserModuleQuestion::withoutGlobalScope('user_scope')
            ->join('timetable_questions', 'user_module_questions.timetable_question_id', '=', 'timetable_questions.id')
            ->join('questions', 'timetable_questions.question_id', '=', 'questions.id')
            ->when($companyId, fn ($q) => $q->where('user_module_questions.company_id', $companyId))
            ->where(function ($q) {
                $q->whereNull('user_module_questions.is_show')
                    ->orWhere('user_module_questions.is_show', true);
            })
            ->groupBy('questions.id')
            ->select([
                'questions.id as question_id',
                DB::raw('count(user_module_questions.id) as total_attempts'),
                DB::raw("sum(case when user_module_questions.status = 'correct' then 1 else 0 end) as total_correct"),
            ])
            ->get();

        if ($rows->isEmpty()) {
            session()->flash('message', 'Tidak ada data untuk digenerate.');

            return;
        }

        DB::transaction(function () use ($rows) {
            foreach ($rows as $row) {
                $total = (int) ($row->total_attempts ?? 0);
                $correct = (int) ($row->total_correct ?? 0);
                if ($total <= 0) {
                    continue;
                }

                $p = $correct / $total;
                $difficulty = $p >= 0.7 ? 'easy' : ($p >= 0.3 ? 'medium' : 'hard');

                Question::where('id', $row->question_id)->update([
                    'difficulty' => $difficulty,
                ]);

                TimetableQuestion::where('question_id', $row->question_id)->update([
                    'difficulty' => $difficulty,
                ]);
            }
        });

        session()->flash('message', 'Generate difficulty selesai.');
    }

    protected function itemsQuery()
    {
        $companyId = auth()->user()?->company_id;

        $query = UserModuleQuestion::withoutGlobalScope('user_scope')
            ->join('timetable_questions', 'user_module_questions.timetable_question_id', '=', 'timetable_questions.id')
            ->join('questions', 'timetable_questions.question_id', '=', 'questions.id')
            ->when($companyId, fn ($q) => $q->where('user_module_questions.company_id', $companyId))
            ->where(function ($q) {
                $q->whereNull('user_module_questions.is_show')
                    ->orWhere('user_module_questions.is_show', true);
            });

        $query = $this->applyQuestionFilters($query, 'questions');

        $query->groupBy('questions.id', 'questions.question', 'questions.difficulty')
            ->select([
                'questions.id as question_id',
                'questions.question as question_text',
                'questions.difficulty as difficulty',
                DB::raw('count(user_module_questions.id) as total_attempts'),
                DB::raw("sum(case when user_module_questions.status = 'correct' then 1 else 0 end) as total_correct"),
            ])
            ->orderBy('questions.question', 'asc');

        return $query;
    }

    public function render()
    {
        $items = $this->itemsQuery()->paginate($this->perPage);
        $ids = $items->pluck('question_id')->all();
        if ($this->selectedQuestionId) {
            $ids[] = $this->selectedQuestionId;
        }
        $analyses = app(ItemAnalysisService::class)->aggregateMany($ids);

        foreach ($items as $item) {
            if (! array_key_exists($item->question_id, $this->difficultySelections)) {
                $this->difficultySelections[$item->question_id] = $item->difficulty ?? 'default';
            }
        }

        return view('livewire.admin.report.item-analysis.admin-report-item-analysis-all-index', [
            'items' => $items,
            'analyses' => $analyses,
            'selectedAnalysis' => $this->selectedQuestionId ? $analyses[$this->selectedQuestionId] : null,
        ])->extends('layout.app')->section('content');
    }
}
