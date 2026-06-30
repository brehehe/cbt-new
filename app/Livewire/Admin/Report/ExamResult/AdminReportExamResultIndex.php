<?php

namespace App\Livewire\Admin\Report\ExamResult;

use App\Exports\ExamResultExport;
use App\Models\Master\Question\Module;
use App\Models\Master\RatingScale\RatingScale;
use App\Models\Master\Timetable\Timetable;
use App\Models\User;
use App\Models\User\UserTimetable;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class AdminReportExamResultIndex extends Component
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
        $examResults = UserTimetable::with(['user', 'timetable.module', 'userModuleQuestions.timetableQuestion.topic'])
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

        $companyId = Auth::user()?->company_id;

        $users = User::query()
            ->where('company_id', $companyId)
            ->orderBy('name')
            ->get(['id', 'name', 'nim', 'username', 'email']);

        $modules = Module::query()
            ->orderBy('name')
            ->get(['id', 'name']);

        $timetables = Timetable::query()
            ->with('module:id,name')
            ->orderBy('start_time', 'desc')
            ->get(['id', 'name', 'module_id', 'start_time', 'end_time']);

        return view('livewire.admin.report.exam-result.admin-report-exam-result-index', [
            'examResults' => $examResults->paginate($this->perPage),
            'users' => $users,
            'modules' => $modules,
            'timetables' => $timetables,
        ])
            ->extends('layout.app')
            ->section('content');
    }

    public function exportPdf()
    {
        $examResults = UserTimetable::with(['user', 'timetable.module', 'userModuleQuestions.timetableQuestion.topic'])
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

        $allTopics = [];
        foreach ($examResults as $result) {
            $gradeDetails[$result->id] = $this->getGradeDetail($result->mark);
            foreach ($result->userModuleQuestions as $umq) {
                $tq = $umq->timetableQuestion;
                if ($tq && $tq->topic_id) {
                    $allTopics[$tq->topic_id] = $tq->topic?->name ?? 'Tanpa Topik';
                }
            }
        }
        asort($allTopics);

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
            if (! isset($gradeDistribution[$letter])) {
                $gradeDistribution[$letter] = 0;
            }
            $gradeDistribution[$letter]++;
        }
        ksort($gradeDistribution);

        $pdf = Pdf::loadView('livewire.admin.report.exam-result.admin-report-exam-result-pdf', [
            'examResults' => $examResults,
            'filterSummary' => $filterSummary,
            'gradeDetails' => $gradeDetails,
            'allTopics' => $allTopics,
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
            fn () => print ($pdf->output()),
            'exam-result-report-'.date('Y-m-d-H-i-s').'.pdf'
        );
    }

    public function exportExcel()
    {
        try {
            $fileName = 'exam-result-report-'.date('YmdHis').'.xlsx';

            return Excel::download(
                new ExamResultExport($this->search, $this->user_id, $this->module_id, $this->timetable_id),
                $fileName
            );
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Exam Result Excel Export Error: '.$e->getMessage());
            session()->flash('error', 'Gagal mengekspor data ke Excel.');
        }
    }

    public function getTopicStats($userTimetable)
    {
        $stats = [];
        foreach ($userTimetable->userModuleQuestions as $umq) {
            $tq = $umq->timetableQuestion;
            if (!$tq) {
                continue;
            }
            $topicName = $tq->topic?->name ?? 'Tanpa Topik';
            $topicId = $tq->topic_id ?? 'no-topic';

            if (!isset($stats[$topicId])) {
                $stats[$topicId] = [
                    'name' => $topicName,
                    'total' => 0,
                    'correct' => 0,
                    'wrong' => 0,
                    'unanswered' => 0,
                ];
            }

            $stats[$topicId]['total']++;

            if (empty($umq->timetable_answer_id)) {
                $stats[$topicId]['unanswered']++;
            } elseif ($umq->status === 'correct') {
                $stats[$topicId]['correct']++;
            } elseif ($umq->status === 'wrong') {
                $stats[$topicId]['wrong']++;
            }
        }
        return $stats;
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
