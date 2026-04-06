<?php

namespace App\Livewire\Admin\Report\ItemAnalysis;

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

        if (!in_array($difficulty, $allowed, true)) {
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

    public function generateAll(): void
    {
        $rows = UserModuleQuestion::withoutGlobalScope('user_scope')
            ->join('timetable_questions', 'user_module_questions.timetable_question_id', '=', 'timetable_questions.id')
            ->join('questions', 'timetable_questions.question_id', '=', 'questions.id')
            ->where('timetable_questions.is_check', true)
            ->where('user_module_questions.company_id', auth()->user()?->company_id)
            ->whereExists(function ($subQuery) {
                $subQuery->select(DB::raw(1))
                    ->from('module_questions')
                    ->whereColumn('module_questions.question_id', 'questions.id')
                    ->where('module_questions.is_check', true);
            })
            ->groupBy('questions.id')
            ->select([
                'questions.id as question_id',
                DB::raw("count(*) as total_attempts"),
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
            ->whereExists(function ($subQuery) {
                $subQuery->select(DB::raw(1))
                    ->from('module_questions')
                    ->whereColumn('module_questions.question_id', 'questions.id')
                    ->where('module_questions.is_check', true);
            })
            ->when($this->search, function ($q) {
                $term = '%' . $this->search . '%';
                $q->where('questions.question', 'ilike', $term);
            })
            ->groupBy('questions.id', 'questions.question', 'questions.difficulty')
            ->select([
                'questions.id as question_id',
                'questions.question as question_text',
                'questions.difficulty as difficulty',
                DB::raw("count(*) as total_attempts"),
                DB::raw("sum(case when user_module_questions.status = 'correct' then 1 else 0 end) as total_correct"),
            ])
            ->orderBy('questions.question');

        $items = $query->paginate($this->perPage);

        foreach ($items as $item) {
            if (!array_key_exists($item->question_id, $this->difficultySelections)) {
                $this->difficultySelections[$item->question_id] = $item->difficulty ?? 'default';
            }
        }

        return view('livewire.admin.report.item-analysis.admin-report-item-analysis-all-index', [
            'items' => $items,
        ])->extends('layout.app')->section('content');
    }
}
