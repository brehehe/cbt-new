<?php

namespace App\Livewire\Admin\Report\ExamResult;

use App\Models\Master\Question\Module;
use App\Models\Master\RatingScale\RatingScale;
use App\Models\Master\Timetable\Timetable;
use App\Models\User;
use App\Models\User\UserTimetable;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use Barryvdh\DomPDF\Facade\Pdf;

class AdminReportExamResultIndex extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $perPage = 10;
    public $search = '';
    public $user_id = '';
    public $module_id = '';
    public $timetable_id = '';

    public $users = [];
    public $modules = [];
    public $timetables = [];

    public function mount()
    {
        $companyId = Auth::user()?->company_id;

        $this->users = User::query()
            ->where('company_id', $companyId)
            ->orderBy('name')
            ->get(['id', 'name', 'nim', 'username', 'email']);

        $this->modules = Module::query()
            ->orderBy('name')
            ->get(['id', 'name']);

        $this->timetables = Timetable::query()
            ->with('module:id,name')
            ->orderBy('start_time', 'desc')
            ->get(['id', 'name', 'module_id', 'start_time', 'end_time']);
    }

    public function getGradeDetail($mark)
    {
        if ($mark === null) {
            return null;
        }

        return RatingScale::where('min_score', '<=', $mark)
            ->where('max_score', '>=', $mark)
            ->orderBy('order')
            ->first();
    }

    public function render()
    {
        $examResults = UserTimetable::with(['user', 'timetable.module'])
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
            ->where('status', 'done')
            ->orderBy('created_at', 'desc');

        return view('livewire.admin.report.exam-result.admin-report-exam-result-index', [
            'examResults' => $examResults->paginate($this->perPage),
        ])
            ->extends('layout.app')
            ->section('content');
    }

    public function exportPdf()
    {
        $examResults = UserTimetable::with(['user', 'timetable.module'])
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
            ->where('status', 'done')
            ->orderBy('created_at', 'desc')
            ->get();

        $filterSummary = $this->getFilterSummary();
        $gradeDetails = [];

        foreach ($examResults as $result) {
            $gradeDetails[$result->id] = $this->getGradeDetail($result->mark);
        }

        $company = Auth::user()->company()->with('companyDetail')->first();

        $count = $examResults->count();
        $stats = [
            'total_students' => $count,
            'average_score' => $count > 0 ? $examResults->avg('mark') : 0,
            'highest_score' => $count > 0 ? $examResults->max('mark') : 0,
            'lowest_score' => $count > 0 ? $examResults->min('mark') : 0,
        ];

        $gradeDistribution = [];
        foreach ($examResults as $result) {
            $grade = $this->getGradeDetail($result->mark);
            $letter = $grade ? $grade->grade_letter : '-';
            if (!isset($gradeDistribution[$letter])) {
                $gradeDistribution[$letter] = 0;
            }
            $gradeDistribution[$letter]++;
        }
        ksort($gradeDistribution);

        $pdf = Pdf::loadView('livewire.admin.report.exam-result.admin-report-exam-result-pdf', [
            'examResults' => $examResults,
            'filterSummary' => $filterSummary,
            'gradeDetails' => $gradeDetails,
            'company' => $company,
            'stats' => $stats,
            'gradeDistribution' => $gradeDistribution,
        ])
            ->setPaper('a4', 'landscape')
            ->setOption('margin-top', 10)
            ->setOption('margin-right', 10)
            ->setOption('margin-bottom', 10)
            ->setOption('margin-left', 10);

        return response()->streamDownload(
            fn () => print($pdf->output()),
            'exam-result-report-' . date('Y-m-d-H-i-s') . '.pdf'
        );
    }

    private function getFilterSummary()
    {
        $summary = [];

        if ($this->search) {
            $summary['search'] = $this->search;
        }

        if ($this->user_id) {
            $user = $this->users->find($this->user_id);
            if ($user) {
                $summary['user'] = $user->name . ' (' . $user->nim . ')';
            }
        }

        if ($this->module_id) {
            $module = $this->modules->find($this->module_id);
            if ($module) {
                $summary['module'] = $module->name;
            }
        }

        if ($this->timetable_id) {
            $timetable = $this->timetables->find($this->timetable_id);
            if ($timetable) {
                $summary['timetable'] = $timetable->name;
            }
        }

        return $summary;
    }
}
