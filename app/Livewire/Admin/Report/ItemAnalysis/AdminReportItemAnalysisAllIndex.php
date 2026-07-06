<?php

namespace App\Livewire\Admin\Report\ItemAnalysis;

use App\Helpers\AlertHelper;
use App\Models\Master\Question\Question;
use App\Models\Timetable\TimetableQuestion;
use App\Models\User\UserModuleQuestion;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class AdminReportItemAnalysisAllIndex extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $perPage = 10;

    public $search = '';

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
            $timetableQuestionIds = TimetableQuestion::where('question_id', $questionId)->pluck('id');

            // Set is_show = false: data tetap ada, tidak ikut dihitung di item analisis.
            // History ujian siswa masih terlihat di halaman riwayat detail.
            UserModuleQuestion::withoutGlobalScope('user_scope')
                ->whereIn('timetable_question_id', $timetableQuestionIds)
                ->where('company_id', auth()->user()?->company_id)
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
            $timetableQuestionIds = TimetableQuestion::where('question_id', $questionId)->pluck('id');

            UserModuleQuestion::withoutGlobalScope('user_scope')
                ->whereIn('timetable_question_id', $timetableQuestionIds)
                ->where('company_id', auth()->user()?->company_id)
                ->update(['is_show' => true]);
        });

        AlertHelper::success('Berhasil', 'Riwayat pengerjaan soal ini ditampilkan kembali di Item Analisis.');
    }

    public function generateAll(): void
    {
        $rows = UserModuleQuestion::withoutGlobalScope('user_scope')
            ->join('timetable_questions', 'user_module_questions.timetable_question_id', '=', 'timetable_questions.id')
            ->join('questions', 'timetable_questions.question_id', '=', 'questions.id')
            ->where('timetable_questions.is_check', true)
            ->where('user_module_questions.company_id', auth()->user()?->company_id)
            ->where('user_module_questions.is_show', true) // hanya yang tidak disembunyikan
            ->whereExists(function ($subQuery) {
                $subQuery->select(DB::raw(1))
                    ->from('module_questions')
                    ->whereColumn('module_questions.question_id', 'questions.id')
                    ->where('module_questions.is_check', true);
            })
            ->groupBy('questions.id')
            ->select([
                'questions.id as question_id',
                DB::raw("sum(case when user_module_questions.status in ('correct', 'wrong') then 1 else 0 end) as total_attempts"),
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

    public function render()
    {
        $query = UserModuleQuestion::withoutGlobalScope('user_scope')
            ->join('timetable_questions', 'user_module_questions.timetable_question_id', '=', 'timetable_questions.id')
            ->join('questions', 'timetable_questions.question_id', '=', 'questions.id')
            ->where('timetable_questions.is_check', true)
            ->where('user_module_questions.company_id', auth()->user()?->company_id)
            ->where('user_module_questions.is_show', true) // hanya yang tidak disembunyikan admin
            ->whereExists(function ($subQuery) {
                $subQuery->select(DB::raw(1))
                    ->from('module_questions')
                    ->whereColumn('module_questions.question_id', 'questions.id')
                    ->where('module_questions.is_check', true);
            })
            ->when($this->search, function ($q) {
                $term = '%'.$this->search.'%';
                $q->where('questions.question', 'like', $term);
            })
            ->groupBy('questions.id', 'questions.question', 'questions.difficulty')
            ->select([
                'questions.id as question_id',
                'questions.question as question_text',
                'questions.difficulty as difficulty',
                DB::raw("sum(case when user_module_questions.status in ('correct', 'wrong') then 1 else 0 end) as total_attempts"),
                DB::raw("sum(case when user_module_questions.status = 'correct' then 1 else 0 end) as total_correct"),
            ])
            ->orderBy('questions.question', 'asc');

        $items = $query->paginate($this->perPage);

        foreach ($items as $item) {
            if (! array_key_exists($item->question_id, $this->difficultySelections)) {
                $this->difficultySelections[$item->question_id] = $item->difficulty ?? 'default';
            }
        }

        return view('livewire.admin.report.item-analysis.admin-report-item-analysis-all-index', [
            'items' => $items,
        ])->extends('layout.app')->section('content');
    }
}
