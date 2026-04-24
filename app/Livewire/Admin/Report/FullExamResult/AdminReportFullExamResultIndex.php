<?php

namespace App\Livewire\Admin\Report\FullExamResult;

use App\Models\Master\Question\Module;
use App\Models\Master\RatingScale\RatingScale;
use App\Models\Master\Timetable\Timetable;
use App\Models\User;
use App\Models\User\UserModuleQuestion;
use App\Models\User\UserTimetable; // Added for answer stats
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
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

    // Process additional stats for a single result result
    public function getResultStats($userTimetableId)
    {
        $questions = UserModuleQuestion::where('user_timetable_id', $userTimetableId)->get();

        return [
            'total' => $questions->count(),
            'correct' => $questions->where('status', 'correct')->count(),
            'wrong' => $questions->where('status', 'wrong')->count(),
            'check' => $questions->where('status', 'check')->count(), // For essay/manual check
            'unanswered' => $questions->whereNull('answer_id')->count(), // Assuming null answer_id means unanswered, or check logic
        ];
    }

    public function render()
    {
        $examResults = UserTimetable::with(['user', 'timetable.module', 'userModuleQuestions']) // Eager load answers for stats
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

        $paginatedResults = $examResults->paginate($this->perPage);

        // Calculate stats for the current page
        $resultStats = [];
        foreach ($paginatedResults as $result) {
            // We can calculate stats from the eager loaded relation to save queries
            $questions = $result->userModuleQuestions;
            $resultStats[$result->id] = [
                'total' => $questions->count(),
                'correct' => $questions->where('status', 'correct')->count(),
                'wrong' => $questions->where('status', 'wrong')->count(),
                'check' => $questions->where('status', 'check')->count(),
                'answered' => $questions->whereNotNull('timetable_answer_id')->count(),
                'unanswered' => $questions->filter(function ($q) {
                    return empty($q->answer_id) && empty($q->essay_answer);
                })->count(),
            ];
        }

        return view('livewire.admin.report.full-exam-result.admin-report-full-exam-result-index', [
            'examResults' => $paginatedResults,
            'resultStats' => $resultStats,
        ])
            ->extends('layout.app')
            ->section('content');
    }

    public function exportPdf()
    {
        // Increase memory limit for PDF generation if needed
        ini_set('memory_limit', '512M');
        ini_set('max_execution_time', 300);

        $examResults = UserTimetable::with(['user', 'timetable.module', 'userModuleQuestions'])
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
        $resultStats = [];

        foreach ($examResults as $result) {
            $gradeDetails[$result->id] = $this->getGradeDetail($result->mark);

            $questions = $result->userModuleQuestions;
            $resultStats[$result->id] = [
                'total' => $questions->count(),
                'correct' => $questions->where('status', 'correct')->count(),
                'wrong' => $questions->where('status', 'wrong')->count(),
                'check' => $questions->where('status', 'check')->count(),
                'answered' => $questions->whereNotNull('timetable_answer_id')->count(), // Explicitly answered
                'unanswered' => $questions->filter(function ($q) {
                    return empty($q->answer_id) && empty($q->essay_answer);
                })->count(),
            ];
        }

        $company = Auth::user()->company()->with('companyDetail')->first();

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
            $user = $this->users->find($this->user_id);
            if ($user) {
                $summary['user'] = $user->name.' ('.$user->nim.')';
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
