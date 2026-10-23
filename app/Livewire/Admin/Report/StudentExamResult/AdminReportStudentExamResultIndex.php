<?php

namespace App\Livewire\Admin\Report\StudentExamResult;

use App\Models\Master\RatingScale\RatingScale;
use App\Models\User;
use App\Models\User\UserTimetable;
use App\Traits\HasQuestionReportFilters;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class AdminReportStudentExamResultIndex extends Component
{
    use HasQuestionReportFilters, WithPagination;

    public $search = '';

    public $user_id = '';

    public $perPage = 10;

    public $sortBy = 'created_at';

    public $sortDirection = 'desc';

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

    // Cache users list for dropdown
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingUserId()
    {
        $this->resetPage();
    }

    public function getGradeDetail($mark)
    {
        return RatingScale::getGrade($mark);
    }

    protected function buildQuery()
    {
        $query = UserTimetable::withoutGlobalScopes()
            ->with(['timetable.module', 'timetable.examSession', 'timetable.examRoom', 'userModuleQuestions.timetableQuestion'])
            ->where('user_id', $this->user_id);

        if (! empty($this->search)) {
            $query->search($this->search);
        }

        if ($this->filterTopicId || $this->filterMaterialCategoryId || $this->filterMaterialId || $this->filterQuestionTypeId || $this->filterCategoryQuestionId) {
            $query->whereHas('userModuleQuestions.timetableQuestion', function ($q) {
                $q->when($this->filterTopicId, fn ($sub) => $sub->where('topic_id', $this->filterTopicId))
                    ->when($this->filterMaterialCategoryId, fn ($sub) => $sub->where('material_category_id', $this->filterMaterialCategoryId))
                    ->when($this->filterMaterialId, fn ($sub) => $sub->where('material_id', $this->filterMaterialId))
                    ->when($this->filterQuestionTypeId, fn ($sub) => $sub->where('question_type_id', $this->filterQuestionTypeId))
                    ->when($this->filterCategoryQuestionId, fn ($sub) => $sub->where('category_question_id', $this->filterCategoryQuestionId));
            });
        }

        $dir = strtolower($this->sortDirection) === 'asc' ? 'asc' : 'desc';

        if ($this->sortBy === 'timetable') {
            $query->join('timetables', 'user_timetables.timetable_id', '=', 'timetables.id')
                ->select('user_timetables.*')
                ->orderByRaw('LOWER(TRIM(timetables.name)) '.$dir);
        } elseif ($this->sortBy === 'mark') {
            $query->orderBy('user_timetables.mark', $dir);
        } else {
            $query->orderBy('user_timetables.created_at', $dir);
        }

        return $query;
    }

    public function render()
    {
        $examResults = collect();

        if ($this->user_id) {
            $examResults = $this->buildQuery()->paginate($this->perPage);
        }

        $companyId = Auth::user()?->company_id;

        // Lightweight user selection: prioritize users who have taken exams, sorted A-Z strictly
        $users = User::query()
            ->when($companyId, fn ($q) => $q->where('company_id', $companyId))
            ->whereHas('userTimetables')
            ->orderByRaw('LOWER(TRIM(name)) ASC')
            ->get(['id', 'name', 'nim', 'username']);

        if ($this->user_id && ! $users->contains('id', $this->user_id)) {
            $selected = User::find($this->user_id, ['id', 'name', 'nim', 'username']);
            if ($selected) {
                $users->prepend($selected);
            }
        }

        return view('livewire.admin.report.student-exam-result.admin-report-student-exam-result-index', [
            'examResults' => $examResults,
            'selectedUser' => $this->user_id ? User::find($this->user_id) : null,
            'users' => $users,
        ])
            ->extends('layout.app')
            ->section('content');
    }

    public function exportPdf()
    {
        @ini_set('memory_limit', '512M');
        @set_time_limit(300);

        if (! $this->user_id) {
            return;
        }

        $user = User::withoutGlobalScopes()->find($this->user_id);
        if (! $user) {
            return;
        }

        $examResults = $this->buildQuery()->get();

        $gradeDetails = [];
        foreach ($examResults as $result) {
            $gradeDetails[$result->id] = $this->getGradeDetail($result->mark);
        }

        $company = Auth::user()?->company ? Auth::user()->company()->with('companyDetail')->first() : \App\Models\Company\Company::with('companyDetail')->first();

        // Calculate summary stats
        $stats = [
            'total_exams' => $examResults->count(),
            'average_score' => $examResults->count() > 0 ? round($examResults->avg('mark'), 2) : 0,
            'passed_exams' => 0,
        ];

        $pdf = Pdf::loadView('livewire.admin.report.student-exam-result.admin-report-student-exam-result-pdf', [
            'user' => $user,
            'examResults' => $examResults,
            'gradeDetails' => $gradeDetails,
            'company' => $company,
            'stats' => $stats,
        ])
            ->setPaper('a4', 'portrait');

        return response()->streamDownload(
            fn () => print ($pdf->output()),
            'hasil-ujian-mahasiswa_'.\Str::slug($user->name).'_'.date('Y-m-d').'.pdf'
        );
    }
}
