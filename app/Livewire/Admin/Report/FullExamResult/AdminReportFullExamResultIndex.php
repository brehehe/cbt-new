<?php

namespace App\Livewire\Admin\Report\FullExamResult;

use App\Models\Master\Question\Module;
use App\Models\Master\RatingScale\RatingScale;
use App\Models\Master\Timetable\Timetable;
use App\Models\User;
use App\Models\User\UserModuleQuestion;
use App\Models\User\UserTimetable;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class AdminReportFullExamResultIndex extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $perPage = 10;

    public $search = '';

    public $user_id = '';

    public $module_id = '';

    public $timetable_id = '';

    public function getGradeDetail($mark)
    {
        return RatingScale::getGrade($mark);
    }

    public function render()
    {
        $examResults = UserTimetable::withoutGlobalScopes()
            ->with(['user:id,name,nim,username', 'timetable:id,name,module_id', 'timetable.module:id,name'])
            ->search($this->search)
            ->when($this->user_id, function ($query) {
                $query->where('user_id', $this->user_id);
            })
            ->when($this->module_id, function ($query) {
                $query->whereHas('timetable', function ($q) {
                    $q->where('module_id', $this->module_id);
                });
            })
            ->when($this->timetable_id, function ($query) {
                $query->where('timetable_id', $this->timetable_id);
            })
            ->orderBy('created_at', 'desc');

        $paginatedResults = $examResults->paginate($this->perPage);

        // Calculate stats for the current page via fast DB aggregation (No N+1)
        $currentPageIds = $paginatedResults->pluck('id');
        $resultStats = [];

        if ($currentPageIds->isNotEmpty()) {
            $statsRows = DB::table('user_module_questions')
                ->whereIn('user_timetable_id', $currentPageIds)
                ->whereNull('deleted_at')
                ->groupBy('user_timetable_id')
                ->select(
                    'user_timetable_id',
                    DB::raw('COUNT(*) as total'),
                    DB::raw("COUNT(CASE WHEN status = 'correct' THEN 1 END) as correct"),
                    DB::raw("COUNT(CASE WHEN status = 'wrong' THEN 1 END) as wrong"),
                    DB::raw("COUNT(CASE WHEN status = 'check' THEN 1 END) as check_count"),
                    DB::raw("COUNT(CASE WHEN timetable_answer_id IS NOT NULL THEN 1 END) as answered"),
                    DB::raw("COUNT(CASE WHEN timetable_answer_id IS NULL AND (essay_answer IS NULL OR essay_answer = '') THEN 1 END) as unanswered")
                )
                ->get();

            foreach ($statsRows as $row) {
                $resultStats[$row->user_timetable_id] = [
                    'total' => (int) $row->total,
                    'correct' => (int) $row->correct,
                    'wrong' => (int) $row->wrong,
                    'check' => (int) $row->check_count,
                    'answered' => (int) $row->answered,
                    'unanswered' => (int) $row->unanswered,
                ];
            }
        }

        $companyId = Auth::user()?->company_id;

        $users = User::query()
            ->when($companyId, fn ($q) => $q->where('company_id', $companyId))
            ->whereHas('userTimetables')
            ->orderBy('name')
            ->limit(300)
            ->get(['id', 'name', 'nim', 'username', 'email']);

        if ($this->user_id && ! $users->contains('id', $this->user_id)) {
            $selected = User::find($this->user_id, ['id', 'name', 'nim', 'username', 'email']);
            if ($selected) {
                $users->prepend($selected);
            }
        }

        $modules = Module::query()
            ->orderBy('name')
            ->get(['id', 'name']);

        $timetables = Timetable::query()
            ->with('module:id,name')
            ->when($companyId, fn ($q) => $q->where('company_id', $companyId))
            ->orderBy('start_time', 'desc')
            ->get(['id', 'name', 'module_id', 'start_time', 'end_time']);

        return view('livewire.admin.report.full-exam-result.admin-report-full-exam-result-index', [
            'examResults' => $paginatedResults,
            'resultStats' => $resultStats,
            'users' => $users,
            'modules' => $modules,
            'timetables' => $timetables,
        ])
            ->extends('layout.app')
            ->section('content');
    }

    public function exportPdf()
    {
        @ini_set('memory_limit', '512M');
        @set_time_limit(300);

        // Fetch lightweight user timetables without thousands of Eloquent question models
        $examResults = UserTimetable::withoutGlobalScopes()
            ->select(['id', 'user_id', 'timetable_id', 'mark', 'start_exam', 'end_exam', 'created_at'])
            ->with([
                'user:id,name,nim,username',
                'timetable:id,name,module_id',
                'timetable.module:id,name',
            ])
            ->search($this->search)
            ->when($this->user_id, function ($query) {
                $query->where('user_id', $this->user_id);
            })
            ->when($this->module_id, function ($query) {
                $query->whereHas('timetable', function ($q) {
                    $q->where('module_id', $this->module_id);
                });
            })
            ->when($this->timetable_id, function ($query) {
                $query->where('timetable_id', $this->timetable_id);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        $userTimetableIds = $examResults->pluck('id');
        $resultStats = [];

        if ($userTimetableIds->isNotEmpty()) {
            $statsRows = DB::table('user_module_questions')
                ->whereIn('user_timetable_id', $userTimetableIds)
                ->whereNull('deleted_at')
                ->groupBy('user_timetable_id')
                ->select(
                    'user_timetable_id',
                    DB::raw('COUNT(*) as total'),
                    DB::raw("COUNT(CASE WHEN status = 'correct' THEN 1 END) as correct"),
                    DB::raw("COUNT(CASE WHEN status = 'wrong' THEN 1 END) as wrong"),
                    DB::raw("COUNT(CASE WHEN status = 'check' THEN 1 END) as check_count"),
                    DB::raw("COUNT(CASE WHEN timetable_answer_id IS NOT NULL THEN 1 END) as answered"),
                    DB::raw("COUNT(CASE WHEN timetable_answer_id IS NULL AND (essay_answer IS NULL OR essay_answer = '') THEN 1 END) as unanswered")
                )
                ->get();

            foreach ($statsRows as $row) {
                $resultStats[$row->user_timetable_id] = [
                    'total' => (int) $row->total,
                    'correct' => (int) $row->correct,
                    'wrong' => (int) $row->wrong,
                    'check' => (int) $row->check_count,
                    'answered' => (int) $row->answered,
                    'unanswered' => (int) $row->unanswered,
                ];
            }
        }

        $filterSummary = $this->getFilterSummary();
        $gradeDetails = [];

        foreach ($examResults as $result) {
            $gradeDetails[$result->id] = $this->getGradeDetail($result->mark);
        }

        $company = Auth::user()?->company ? Auth::user()->company()->with('companyDetail')->first() : \App\Models\Company\Company::with('companyDetail')->first();

        $pdf = Pdf::loadView('livewire.admin.report.full-exam-result.admin-report-full-exam-result-pdf', [
            'examResults' => $examResults,
            'filterSummary' => $filterSummary,
            'gradeDetails' => $gradeDetails,
            'resultStats' => $resultStats,
            'company' => $company,
        ])
            ->setPaper('a4', 'landscape')
            ->setOption('margin-top', 10)
            ->setOption('margin-right', 10)
            ->setOption('margin-bottom', 10)
            ->setOption('margin-left', 10);

        return response()->streamDownload(
            fn () => print ($pdf->output()),
            'laporan-hasil-lengkap-'.date('Y-m-d-H-i-s').'.pdf'
        );
    }

    private function getFilterSummary()
    {
        $summary = [];

        if ($this->search) {
            $summary['search'] = $this->search;
        }

        if ($this->user_id) {
            $user = User::find($this->user_id);
            if ($user) {
                $summary['user'] = $user->name.' ('.$user->nim.')';
            }
        }

        if ($this->module_id) {
            $module = Module::find($this->module_id);
            if ($module) {
                $summary['module'] = $module->name;
            }
        }

        if ($this->timetable_id) {
            $timetable = Timetable::find($this->timetable_id);
            if ($timetable) {
                $summary['timetable'] = $timetable->name;
            }
        }

        return $summary;
    }
}
