<?php

namespace App\Livewire\Admin\Report\ExamResult;

use App\Exports\ExamResultExport;
use App\Models\Master\Question\Module;
use App\Models\Master\RatingScale\RatingScale;
use App\Models\Master\Timetable\Timetable;
use App\Models\User;
use App\Models\User\UserTimetable;
use App\Traits\HasQuestionReportFilters;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class AdminReportExamResultIndex extends Component
{
    use HasQuestionReportFilters, WithPagination;

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
            ->with(['user', 'timetable.module', 'userModuleQuestions.timetableQuestion.topic'])
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
            ->when($this->filterTopicId || $this->filterMaterialCategoryId || $this->filterMaterialId || $this->filterQuestionTypeId || $this->filterCategoryQuestionId, function ($query) {
                $query->whereHas('userModuleQuestions.timetableQuestion', function ($q) {
                    $q->when($this->filterTopicId, fn ($sub) => $sub->where('topic_id', $this->filterTopicId))
                        ->when($this->filterMaterialCategoryId, fn ($sub) => $sub->where('material_category_id', $this->filterMaterialCategoryId))
                        ->when($this->filterMaterialId, fn ($sub) => $sub->where('material_id', $this->filterMaterialId))
                        ->when($this->filterQuestionTypeId, fn ($sub) => $sub->where('question_type_id', $this->filterQuestionTypeId))
                        ->when($this->filterCategoryQuestionId, fn ($sub) => $sub->where('category_question_id', $this->filterCategoryQuestionId));
                });
            })
            ->orderBy('created_at', 'desc');

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
            ->when($companyId, fn ($q) => $q->where('company_id', $companyId))
            ->orderBy('name')
            ->get(['id', 'name']);

        $timetables = Timetable::query()
            ->with('module:id,name')
            ->when($companyId, fn ($q) => $q->where('company_id', $companyId))
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
        @ini_set('memory_limit', '512M');
        @set_time_limit(300);

        // Fetch lightweight user timetables without eager loading 100k+ userModuleQuestions
        $examResults = UserTimetable::query()
            ->select(['id', 'user_id', 'timetable_id', 'mark', 'created_at'])
            ->with([
                'user:id,name,nim,username',
                'timetable:id,name,module_id,start_time,end_time',
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

        $overallStats = [];
        $topicStatsByUser = [];
        $allTopics = [];

        if ($userTimetableIds->isNotEmpty()) {
            // Aggregate question counts directly in PostgreSQL
            $overallRows = DB::table('user_module_questions')
                ->whereIn('user_timetable_id', $userTimetableIds)
                ->whereNull('deleted_at')
                ->groupBy('user_timetable_id')
                ->select(
                    'user_timetable_id',
                    DB::raw('COUNT(*) as total_questions'),
                    DB::raw("COUNT(CASE WHEN status = 'correct' THEN 1 END) as correct"),
                    DB::raw("COUNT(CASE WHEN status = 'wrong' THEN 1 END) as wrong"),
                    DB::raw("COUNT(CASE WHEN timetable_answer_id IS NULL THEN 1 END) as unanswered")
                )
                ->get();

            foreach ($overallRows as $row) {
                $overallStats[$row->user_timetable_id] = [
                    'total' => (int) $row->total_questions,
                    'correct' => (int) $row->correct,
                    'wrong' => (int) $row->wrong,
                    'unanswered' => (int) $row->unanswered,
                ];
            }

            // Aggregate topic breakdown counts directly in PostgreSQL
            $topicRows = DB::table('user_module_questions as umq')
                ->join('timetable_questions as tq', 'tq.id', '=', 'umq.timetable_question_id')
                ->leftJoin('topics as top', 'top.id', '=', 'tq.topic_id')
                ->whereIn('umq.user_timetable_id', $userTimetableIds)
                ->whereNull('umq.deleted_at')
                ->whereNotNull('tq.topic_id')
                ->groupBy('umq.user_timetable_id', 'tq.topic_id', 'top.name')
                ->select(
                    'umq.user_timetable_id',
                    'tq.topic_id',
                    DB::raw("COALESCE(top.name, 'Tanpa Topik') as topic_name"),
                    DB::raw('COUNT(*) as total_questions'),
                    DB::raw("COUNT(CASE WHEN umq.status = 'correct' THEN 1 END) as correct"),
                    DB::raw("COUNT(CASE WHEN umq.status = 'wrong' THEN 1 END) as wrong"),
                    DB::raw("COUNT(CASE WHEN umq.timetable_answer_id IS NULL THEN 1 END) as unanswered")
                )
                ->get();

            foreach ($topicRows as $row) {
                $allTopics[$row->topic_id] = $row->topic_name;
                $topicStatsByUser[$row->user_timetable_id][$row->topic_id] = [
                    'total' => (int) $row->total_questions,
                    'correct' => (int) $row->correct,
                    'wrong' => (int) $row->wrong,
                    'unanswered' => (int) $row->unanswered,
                ];
            }
            asort($allTopics);
        }

        $filterSummary = $this->getFilterSummary();
        $company = Auth::user()?->company ? Auth::user()->company()->with('companyDetail')->first() : \App\Models\Company\Company::with('companyDetail')->first();

        // Rating scales loaded once
        $ratingScales = RatingScale::orderBy('order')->get();
        $gradeDistribution = [];
        $rows = [];

        foreach ($examResults as $result) {
            $mark = $result->mark;
            $scale = null;
            if ($mark !== null) {
                $scale = $ratingScales->first(function ($item) use ($mark) {
                    return $item->min_score <= $mark && $item->max_score >= $mark;
                });
            }
            $gradeLetter = $scale?->grade_letter ?? '-';
            if (! isset($gradeDistribution[$gradeLetter])) {
                $gradeDistribution[$gradeLetter] = 0;
            }
            $gradeDistribution[$gradeLetter]++;

            $overall = $overallStats[$result->id] ?? [
                'total' => 0,
                'correct' => 0,
                'wrong' => 0,
                'unanswered' => 0,
            ];

            $rows[] = [
                'id' => $result->id,
                'name' => $result->user?->name ?? '-',
                'nim' => $result->user?->nim ?? ($result->user?->username ?? '-'),
                'module' => $result->timetable?->module?->name ?? '-',
                'timetable' => $result->timetable?->name ?? '-',
                'start_time' => $result->timetable?->start_time?->format('d/m/y H:i') ?? '-',
                'end_time' => $result->timetable?->end_time?->format('d/m/y H:i') ?? '-',
                'total' => $overall['total'],
                'correct' => $overall['correct'],
                'wrong' => $overall['wrong'],
                'unanswered' => $overall['unanswered'],
                'mark' => $mark !== null ? number_format((float) $mark, 2) : '-',
                'grade_letter' => $gradeLetter,
                'topic_stats' => $topicStatsByUser[$result->id] ?? [],
            ];
        }
        ksort($gradeDistribution);

        $count = count($rows);
        $numericMarks = $examResults->pluck('mark')->filter(fn ($m) => !is_null($m));
        $stats = [
            'total_students' => $count,
            'average_score' => $numericMarks->isNotEmpty() ? $numericMarks->avg() : 0,
            'highest_score' => $numericMarks->isNotEmpty() ? $numericMarks->max() : 0,
            'lowest_score' => $numericMarks->isNotEmpty() ? $numericMarks->min() : 0,
        ];

        $pdf = Pdf::loadView('livewire.admin.report.exam-result.admin-report-exam-result-pdf', [
            'rows' => $rows,
            'filterSummary' => $filterSummary,
            'allTopics' => $allTopics,
            'company' => $company,
            'stats' => $stats,
            'gradeDistribution' => $gradeDistribution,
        ])
            ->setPaper('a4', 'landscape')
            ->setOption('isHtml5ParserEnabled', true)
            ->setOption('margin-top', 8)
            ->setOption('margin-right', 8)
            ->setOption('margin-bottom', 8)
            ->setOption('margin-left', 8);

        return response()->streamDownload(
            fn () => print ($pdf->output()),
            'exam-result-report-'.date('Y-m-d-H-i-s').'.pdf'
        );
    }

    public function exportExcel()
    {
        try {
            @ini_set('memory_limit', '512M');
            @set_time_limit(300);

            $fileName = 'exam-result-report-'.date('YmdHis').'.xlsx';

            return Excel::download(
                new ExamResultExport($this->search, $this->user_id, $this->module_id, $this->timetable_id),
                $fileName
            );
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Exam Result Excel Export Error: '.$e->getMessage());
            session()->flash('error', 'Gagal mengekspor data ke Excel: '.$e->getMessage());
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

            if ($this->filterTopicId && $tq->topic_id !== $this->filterTopicId) {
                continue;
            }
            if ($this->filterMaterialCategoryId && $tq->material_category_id !== $this->filterMaterialCategoryId) {
                continue;
            }
            if ($this->filterMaterialId && $tq->material_id !== $this->filterMaterialId) {
                continue;
            }
            if ($this->filterQuestionTypeId && $tq->question_type_id !== $this->filterQuestionTypeId) {
                continue;
            }
            if ($this->filterCategoryQuestionId && $tq->category_question_id !== $this->filterCategoryQuestionId) {
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
