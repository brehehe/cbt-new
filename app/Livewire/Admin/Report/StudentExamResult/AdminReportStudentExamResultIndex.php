<?php

namespace App\Livewire\Admin\Report\StudentExamResult;

use App\Models\Master\RatingScale\RatingScale;
use App\Models\User;
use App\Models\User\UserTimetable;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class AdminReportStudentExamResultIndex extends Component
{
    use WithPagination;

    public $search = '';

    public $user_id = '';

    public $perPage = 10;

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

    public function render()
    {
        $examResults = collect();

        if ($this->user_id) {
            $examResults = UserTimetable::withoutGlobalScopes()
                ->with(['timetable.module', 'timetable.examSession', 'timetable.examRoom'])
                ->where('user_id', $this->user_id)
                ->where(function ($query) {
                    $query->whereHas('timetable', function ($q) {
                        $q->search($this->search);
                    });
                })
                ->orderBy('created_at', 'desc')
                ->paginate($this->perPage);
        }

        $companyId = Auth::user()?->company_id;

        // Lightweight user selection: prioritize users who have taken exams
        $users = User::query()
            ->when($companyId, fn ($q) => $q->where('company_id', $companyId))
            ->whereHas('userTimetables')
            ->orderBy('name', 'asc')
            ->limit(300)
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

        $examResults = UserTimetable::withoutGlobalScopes()
            ->with(['timetable.module', 'timetable.examSession', 'timetable.examRoom'])
            ->where('user_id', $this->user_id)
            ->where(function ($query) {
                $query->whereHas('timetable', function ($q) {
                    $q->search($this->search);
                });
            })
            ->orderBy('created_at', 'desc')
            ->get();

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
