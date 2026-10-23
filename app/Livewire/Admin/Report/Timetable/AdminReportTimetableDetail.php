<?php

namespace App\Livewire\Admin\Report\Timetable;

use App\Models\Timetable\TimetableAnswer;
use App\Models\Timetable\TimetableModule;
use App\Models\User\UserModuleQuestion;
use App\Models\User\UserTimetable;
use App\Traits\HasQuestionReportFilters;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Schema;
use Livewire\Component;
use Livewire\WithPagination;

class AdminReportTimetableDetail extends Component
{
    use HasQuestionReportFilters, WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $perPage = 10;

    public $search;

    public $questionSearch = '';

    public $sortBy = 'name';

    public $sortDirection = 'asc';

    public $timetable_module;

    public function setSorting(string $field): void
    {
        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $field;
            $this->sortDirection = 'asc';
        }
        $this->resetPage();
    }

    public function toggleSortDirection(): void
    {
        $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        $this->resetPage();
    }

    public function updatedQuestionSearch()
    {
        $this->resetPage();
    }

    public function resetQuestionFilters(): void
    {
        $this->filterTopicId = '';
        $this->filterMaterialCategoryId = '';
        $this->filterMaterialId = '';
        $this->filterQuestionTypeId = '';
        $this->filterCategoryQuestionId = '';
        $this->questionSearch = '';
        $this->resetPage();
    }

    public function hasActiveQuestionFilters(): bool
    {
        return ! empty($this->filterTopicId)
            || ! empty($this->filterMaterialCategoryId)
            || ! empty($this->filterMaterialId)
            || ! empty($this->filterQuestionTypeId)
            || ! empty($this->filterCategoryQuestionId)
            || ! empty($this->questionSearch);
    }

    public function render()
    {
        $hasEssayAnswerColumn = $this->hasEssayAnswerColumn();
        $timetable_questions = $this->getTimetableQuestions();

        $questionSelects = ['id', 'user_timetable_id', 'timetable_question_id', 'status'];
        if ($hasEssayAnswerColumn) {
            $questionSelects[] = 'essay_answer';
        }

        $timetableId = $this->timetable_module?->timetable_id;

        $dir = strtolower($this->sortDirection) === 'desc' ? 'desc' : 'asc';

        $query = UserTimetable::withoutGlobalScopes()
            ->where('timetable_id', $timetableId)
            ->search($this->search)
            ->with([
                'user',
                'userModuleQuestions' => function ($q) use ($questionSelects) {
                    $q->select($questionSelects);
                },
            ]);

        if ($this->sortBy === 'name') {
            $query->join('users', 'user_timetables.user_id', '=', 'users.id')
                ->select('user_timetables.*')
                ->orderByRaw('LOWER(TRIM(users.name)) '.$dir);
        } elseif ($this->sortBy === 'nim') {
            $query->join('users', 'user_timetables.user_id', '=', 'users.id')
                ->select('user_timetables.*')
                ->orderByRaw('LOWER(TRIM(COALESCE(users.nim, users.username, \'\'))) '.$dir);
        } elseif ($this->sortBy === 'mark') {
            $query->orderBy('user_timetables.mark', $dir);
        } else {
            $query->orderBy('user_timetables.created_at', $dir);
        }

        $user_timetables = $query->paginate($this->perPage);

        return view('livewire.admin.report.timetable.admin-report-timetable-detail', [
            'user_timetables' => $user_timetables,
            'timetable_questions' => $timetable_questions,
        ])->extends('layout.app')->section('content');
    }

    protected function filterQuestionsQuery($query)
    {
        $query->when($this->filterTopicId, fn ($q) => $q->where('timetable_questions.topic_id', $this->filterTopicId))
            ->when($this->filterMaterialCategoryId, fn ($q) => $q->where('timetable_questions.material_category_id', $this->filterMaterialCategoryId))
            ->when($this->filterMaterialId, fn ($q) => $q->where('timetable_questions.material_id', $this->filterMaterialId))
            ->when($this->filterQuestionTypeId, fn ($q) => $q->where('timetable_questions.question_type_id', $this->filterQuestionTypeId))
            ->when($this->filterCategoryQuestionId, fn ($q) => $q->where('timetable_questions.category_question_id', $this->filterCategoryQuestionId));

        if (! empty($this->questionSearch)) {
            $query->search($this->questionSearch);
        }

        return $query;
    }

    public function getTimetableQuestions()
    {
        $questions = collect();

        if ($this->timetable_module) {
            $qQuery = $this->timetable_module->questions()
                ->withoutGlobalScopes()
                ->with(['question']);
            $questions = $this->filterQuestionsQuery($qQuery)
                ->orderBy('order', 'asc')
                ->get();
        }

        if ($questions->isEmpty() && $this->timetable_module) {
            $timetableId = $this->timetable_module->timetable_id;
            $qQuery = \App\Models\Timetable\TimetableQuestion::withoutGlobalScopes()
                ->with(['question'])
                ->where(function ($q) use ($timetableId) {
                    $q->whereHas('timetableModule', fn($tm) => $tm->where('timetable_id', $timetableId))
                        ->orWhere('timetable_module_id', $timetableId)
                        ->orWhere('timetable_module_id', $this->timetable_module->id);
                });
            $questions = $this->filterQuestionsQuery($qQuery)
                ->orderBy('order', 'asc')
                ->get();
        }

        if ($questions->isEmpty() && $this->timetable_module) {
            $timetableId = $this->timetable_module->timetable_id;
            $questionIds = UserModuleQuestion::withoutGlobalScopes()
                ->whereHas('userTimetable', fn($q) => $q->withoutGlobalScopes()->where('timetable_id', $timetableId))
                ->whereNotNull('timetable_question_id')
                ->pluck('timetable_question_id')
                ->unique()
                ->values();

            if ($questionIds->isNotEmpty()) {
                $qQuery = \App\Models\Timetable\TimetableQuestion::withoutGlobalScopes()
                    ->with(['question'])
                    ->whereIn('id', $questionIds);
                $questions = $this->filterQuestionsQuery($qQuery)
                    ->orderBy('order', 'asc')
                    ->get();
            }
        }

        return $questions;
    }

    public function exportPdf()
    {
        @ini_set('memory_limit', '512M');
        @set_time_limit(300);

        $hasEssayAnswerColumn = $this->hasEssayAnswerColumn();
        $questions = $this->getTimetableQuestions();
        $questionIds = $questions->pluck('id')->filter()->values();
        $timetableId = $this->timetable_module?->timetable_id;

        $user_timetables = UserTimetable::withoutGlobalScopes()
            ->where('timetable_id', $timetableId)
            ->with(['user'])
            ->get();

        $userTimetableIds = $user_timetables->pluck('id')->filter()->values();

        $answerMap = TimetableAnswer::withoutGlobalScopes()
            ->whereIn('timetable_question_id', $questionIds)
            ->where('is_correct', true)
            ->get(['timetable_question_id', 'order'])
            ->groupBy('timetable_question_id')
            ->map(function ($items) {
                $order = $items->first()?->order;

                return $order ? chr(64 + $order) : '-';
            })
            ->toArray();

        $statusSelects = ['user_timetable_id', 'timetable_question_id', 'status'];
        if ($hasEssayAnswerColumn) {
            $statusSelects[] = 'essay_answer';
        }

        $userQuestionStatuses = UserModuleQuestion::withoutGlobalScopes()
            ->whereIn('user_timetable_id', $userTimetableIds)
            ->whereIn('timetable_question_id', $questionIds)
            ->get($statusSelects)
            ->groupBy('user_timetable_id')
            ->map(fn($items) => $items->keyBy('timetable_question_id'));

        $correctCounts = [];
        foreach ($user_timetables as $userTimetable) {
            $userAnswers = $userQuestionStatuses->get($userTimetable->id) ?? collect();
            $count = $userAnswers->where('status', 'correct')->count();
            $correctCounts[$userTimetable->id] = $count;
        }

        $pdf = Pdf::loadView('livewire.admin.report.timetable.admin-report-timetable-detail-pdf', [
            'timetable_module' => $this->timetable_module,
            'timetable_questions' => $questions,
            'user_timetables' => $user_timetables,
            'answerMap' => $answerMap,
            'userQuestionStatuses' => $userQuestionStatuses,
            'correctCounts' => $correctCounts,
        ])->setPaper('a4', 'landscape');

        $fileName = 'rekap-jadwal-'.($this->timetable_module?->timetable_id ?? 'timetable').'.pdf';

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, $fileName);
    }

    public function mount($id)
    {
        $this->timetable_module = TimetableModule::withoutGlobalScopes()
            ->with(['module'])
            ->where('timetable_id', $id)
            ->first();

        if (! $this->timetable_module) {
            $this->timetable_module = TimetableModule::withoutGlobalScopes()
                ->with(['module'])
                ->find($id);
        }

        if (! $this->timetable_module) {
            abort(404, 'Data jadwal ujian tidak ditemukan.');
        }
    }

    public function getAnswerCorrect($timetable_question_id)
    {
        $timetable_question = TimetableAnswer::withoutGlobalScopes()
            ->where('timetable_question_id', $timetable_question_id)
            ->orderBy('order', 'asc')
            ->get();

        foreach ($timetable_question as $key => $value) {
            if ($value->is_correct) {
                $letter = $value->order ? chr(64 + $value->order) : chr(65 + $key);
                return [$letter, $value];
            }
        }

        return '-';
    }

    private function hasEssayAnswerColumn(): bool
    {
        static $hasColumn;

        if ($hasColumn === null) {
            $hasColumn = Schema::hasColumn('user_module_questions', 'essay_answer');
        }

        return $hasColumn;
    }
}
