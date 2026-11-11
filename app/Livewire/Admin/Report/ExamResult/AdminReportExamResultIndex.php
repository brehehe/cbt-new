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

    public $sortBy = 'name';

    public $sortDirection = 'asc';

    protected $cachedOverallStats = [];

    protected $cachedTopicStatsByUser = [];

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

    public function getGradeDetail($mark)
    {
        return RatingScale::getGrade($mark);
    }

    public function render()
    {
        $companyId = Auth::user()?->company_id;

        $examResults = UserTimetable::withoutGlobalScopes()
            ->when($companyId, fn ($q) => $q->where('user_timetables.company_id', $companyId))
            ->select(['user_timetables.id', 'user_timetables.user_id', 'user_timetables.timetable_id', 'user_timetables.mark', 'user_timetables.created_at'])
            ->with([
                'user:id,name,nim,username',
                'timetable:id,name,module_id,start_time,end_time',
                'timetable.module:id,name',
            ])
            ->search($this->search)
            ->when($this->user_id, function ($query) {
                $query->where('user_timetables.user_id', $this->user_id);
            })
            ->when($this->module_id, function ($query) {
                $query->whereHas('timetable', function ($q) {
                    $q->where('module_id', $this->module_id);
                });
            })
            ->when($this->timetable_id, function ($query) {
                $query->where('user_timetables.timetable_id', $this->timetable_id);
            })
            ->when($this->isValidUuid($this->filterTopicId) || $this->isValidUuid($this->filterMaterialCategoryId) || $this->isValidUuid($this->filterMaterialId) || $this->isValidUuid($this->filterQuestionTypeId) || $this->isValidUuid($this->filterCategoryQuestionId), function ($query) {
                $query->whereHas('userModuleQuestions.timetableQuestion', function ($q) {
                    $q->when($this->isValidUuid($this->filterTopicId), fn ($sub) => $sub->where('topic_id', $this->filterTopicId))
                        ->when($this->isValidUuid($this->filterMaterialCategoryId), fn ($sub) => $sub->where('material_category_id', $this->filterMaterialCategoryId))
                        ->when($this->isValidUuid($this->filterMaterialId), fn ($sub) => $sub->where('material_id', $this->filterMaterialId))
                        ->when($this->isValidUuid($this->filterQuestionTypeId), fn ($sub) => $sub->where('question_type_id', $this->filterQuestionTypeId))
                        ->when($this->isValidUuid($this->filterCategoryQuestionId), fn ($sub) => $sub->where('category_question_id', $this->filterCategoryQuestionId));
                });
            });

        $dir = strtolower($this->sortDirection) === 'desc' ? 'desc' : 'asc';

        if ($this->sortBy === 'name') {
            $examResults->join('users', 'user_timetables.user_id', '=', 'users.id')
                ->select('user_timetables.*')
                ->orderByRaw('LOWER(TRIM(users.name)) '.$dir);
        } elseif ($this->sortBy === 'nim') {
            $examResults->join('users', 'user_timetables.user_id', '=', 'users.id')
                ->select('user_timetables.*')
                ->orderByRaw('LOWER(TRIM(COALESCE(users.nim, users.username, \'\'))) '.$dir);
        } elseif ($this->sortBy === 'timetable') {
            $examResults->join('timetables', 'user_timetables.timetable_id', '=', 'timetables.id')
                ->select('user_timetables.*')
                ->orderByRaw('LOWER(TRIM(timetables.name)) '.$dir);
        } elseif ($this->sortBy === 'mark') {
            $examResults->orderBy('user_timetables.mark', $dir);
        } else {
            $examResults->orderBy('user_timetables.created_at', $dir);
        }

        $paginatedResults = $examResults->paginate($this->perPage);

        // Pre-aggregate overall question stats and topic breakdown in DB for current page (No N+1, No memory bloat)
        $currentPageIds = $paginatedResults->pluck('id')->all();
        $overallStats = [];
        $topicStatsByUser = [];

        if (! empty($currentPageIds)) {
            $hasQuestionFilter = $this->isValidUuid($this->filterTopicId)
                || $this->isValidUuid($this->filterMaterialCategoryId)
                || $this->isValidUuid($this->filterMaterialId)
                || $this->isValidUuid($this->filterQuestionTypeId)
                || $this->isValidUuid($this->filterCategoryQuestionId);

            $overallQuery = DB::table('user_module_questions as umq')
                ->whereIn('umq.user_timetable_id', $currentPageIds)
                ->whereNull('umq.deleted_at');

            if ($hasQuestionFilter) {
                $overallQuery->join('timetable_questions as tq_filter', 'tq_filter.id', '=', 'umq.timetable_question_id')
                    ->when($this->isValidUuid($this->filterTopicId), fn ($q) => $q->where('tq_filter.topic_id', $this->filterTopicId))
                    ->when($this->isValidUuid($this->filterMaterialCategoryId), fn ($q) => $q->where('tq_filter.material_category_id', $this->filterMaterialCategoryId))
                    ->when($this->isValidUuid($this->filterMaterialId), fn ($q) => $q->where('tq_filter.material_id', $this->filterMaterialId))
                    ->when($this->isValidUuid($this->filterQuestionTypeId), fn ($q) => $q->where('tq_filter.question_type_id', $this->filterQuestionTypeId))
                    ->when($this->isValidUuid($this->filterCategoryQuestionId), fn ($q) => $q->where('tq_filter.category_question_id', $this->filterCategoryQuestionId));
            }

            $overallRows = $overallQuery->groupBy('umq.user_timetable_id')
                ->select(
                    'umq.user_timetable_id',
                    DB::raw('COUNT(*) as total_questions'),
                    DB::raw("COUNT(CASE WHEN umq.status = 'correct' THEN 1 END) as correct"),
                    DB::raw("COUNT(CASE WHEN umq.status = 'wrong' THEN 1 END) as wrong"),
                    DB::raw("COUNT(CASE WHEN umq.timetable_answer_id IS NULL THEN 1 END) as unanswered")
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

            $topicQuery = DB::table('user_module_questions as umq')
                ->join('timetable_questions as tq', 'tq.id', '=', 'umq.timetable_question_id')
                ->leftJoin('topics as top', 'top.id', '=', 'tq.topic_id')
                ->whereIn('umq.user_timetable_id', $currentPageIds)
                ->whereNull('umq.deleted_at')
                ->whereNotNull('tq.topic_id')
                ->when($this->filterTopicId, fn ($q) => $q->where('tq.topic_id', $this->filterTopicId))
                ->when($this->filterMaterialCategoryId, fn ($q) => $q->where('tq.material_category_id', $this->filterMaterialCategoryId))
                ->when($this->filterMaterialId, fn ($q) => $q->where('tq.material_id', $this->filterMaterialId))
                ->when($this->filterQuestionTypeId, fn ($q) => $q->where('tq.question_type_id', $this->filterQuestionTypeId))
                ->when($this->filterCategoryQuestionId, fn ($q) => $q->where('tq.category_question_id', $this->filterCategoryQuestionId));

            $topicRows = $topicQuery->groupBy('umq.user_timetable_id', 'tq.topic_id', 'top.name')
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
                $topicStatsByUser[$row->user_timetable_id][$row->topic_id] = [
                    'name' => $row->topic_name,
                    'total' => (int) $row->total_questions,
                    'correct' => (int) $row->correct,
                    'wrong' => (int) $row->wrong,
                    'unanswered' => (int) $row->unanswered,
                ];
            }
        }

        $this->cachedOverallStats = $overallStats;
        $this->cachedTopicStatsByUser = $topicStatsByUser;

        $users = User::query()
            ->when($companyId, fn ($q) => $q->where('company_id', $companyId))
            ->whereHas('userTimetables', function ($q) {
                $q->when($this->timetable_id, fn ($sq) => $sq->where('timetable_id', $this->timetable_id));
            })
            ->orderByRaw('LOWER(TRIM(name)) ASC')
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
            'examResults' => $paginatedResults,
            'overallStats' => $overallStats,
            'topicStatsByUser' => $topicStatsByUser,
            'users' => $users,
            'modules' => $modules,
            'timetables' => $timetables,
        ])
            ->extends('layout.app')
            ->section('content');
    }

    public function exportPdf()
    {
        @ini_set('memory_limit', '1024M');
        @set_time_limit(600);
        $this->skipRender();

        try {
            $companyId = Auth::user()?->company_id;

            // Direct DB::table query with joins - zero Eloquent model hydration overhead
            $query = DB::table('user_timetables as ut')
                ->leftJoin('users as u', 'u.id', '=', 'ut.user_id')
                ->leftJoin('timetables as t', 't.id', '=', 'ut.timetable_id')
                ->leftJoin('modules as m', 'm.id', '=', 't.module_id')
                ->whereNull('ut.deleted_at')
                ->when($companyId, fn ($q) => $q->where('ut.company_id', $companyId))
                ->when($this->user_id, fn ($q) => $q->where('ut.user_id', $this->user_id))
                ->when($this->module_id, fn ($q) => $q->where('t.module_id', $this->module_id))
                ->when($this->timetable_id, fn ($q) => $q->where('ut.timetable_id', $this->timetable_id));

            if (! empty($this->search)) {
                $term = '%' . trim($this->search) . '%';
                $query->where(function ($q) use ($term) {
                    $q->where('u.name', 'ilike', $term)
                        ->orWhere('u.nim', 'ilike', $term)
                        ->orWhere('u.username', 'ilike', $term)
                        ->orWhere('t.name', 'ilike', $term)
                        ->orWhere('m.name', 'ilike', $term);
                });
            }

            $hasQuestionFilter = $this->isValidUuid($this->filterTopicId)
                || $this->isValidUuid($this->filterMaterialCategoryId)
                || $this->isValidUuid($this->filterMaterialId)
                || $this->isValidUuid($this->filterQuestionTypeId)
                || $this->isValidUuid($this->filterCategoryQuestionId);

            if ($hasQuestionFilter) {
                $query->whereExists(function ($sub) {
                    $sub->select(DB::raw(1))
                        ->from('user_module_questions as sub_umq')
                        ->join('timetable_questions as sub_tq', 'sub_tq.id', '=', 'sub_umq.timetable_question_id')
                        ->whereColumn('sub_umq.user_timetable_id', 'ut.id')
                        ->when($this->isValidUuid($this->filterTopicId), fn ($q) => $q->where('sub_tq.topic_id', $this->filterTopicId))
                        ->when($this->isValidUuid($this->filterMaterialCategoryId), fn ($q) => $q->where('sub_tq.material_category_id', $this->filterMaterialCategoryId))
                        ->when($this->isValidUuid($this->filterMaterialId), fn ($q) => $q->where('sub_tq.material_id', $this->filterMaterialId))
                        ->when($this->isValidUuid($this->filterQuestionTypeId), fn ($q) => $q->where('sub_tq.question_type_id', $this->filterQuestionTypeId))
                        ->when($this->isValidUuid($this->filterCategoryQuestionId), fn ($q) => $q->where('sub_tq.category_question_id', $this->filterCategoryQuestionId));
                });
            }

            $examResults = $query->orderBy('ut.created_at', 'desc')
                ->select([
                    'ut.id',
                    'ut.user_id',
                    'ut.timetable_id',
                    'ut.mark',
                    'ut.created_at',
                    'u.name as user_name',
                    'u.nim as user_nim',
                    'u.username as user_username',
                    't.name as timetable_name',
                    't.start_time as timetable_start_time',
                    't.end_time as timetable_end_time',
                    'm.name as module_name',
                ])
                ->get();

            $userTimetableIds = $examResults->pluck('id')->all();

            $overallStats = [];
            $topicStatsByUser = [];
            $allTopics = [];

            if (! empty($userTimetableIds)) {
                $hasQuestionFilter = $this->filterTopicId || $this->filterMaterialCategoryId || $this->filterMaterialId || $this->filterQuestionTypeId || $this->filterCategoryQuestionId;

                // Chunk queries in 500-record blocks to prevent PostgreSQL parameter limits and RAM spikes
                foreach (array_chunk($userTimetableIds, 500) as $chunkIds) {
                    $overallQuery = DB::table('user_module_questions as umq')
                        ->whereIn('umq.user_timetable_id', $chunkIds)
                        ->whereNull('umq.deleted_at');

                    if ($hasQuestionFilter) {
                        $overallQuery->join('timetable_questions as sub_tq', 'sub_tq.id', '=', 'umq.timetable_question_id')
                            ->when($this->isValidUuid($this->filterTopicId), fn ($q) => $q->where('sub_tq.topic_id', $this->filterTopicId))
                            ->when($this->isValidUuid($this->filterMaterialCategoryId), fn ($q) => $q->where('sub_tq.material_category_id', $this->filterMaterialCategoryId))
                            ->when($this->isValidUuid($this->filterMaterialId), fn ($q) => $q->where('sub_tq.material_id', $this->filterMaterialId))
                            ->when($this->isValidUuid($this->filterQuestionTypeId), fn ($q) => $q->where('sub_tq.question_type_id', $this->filterQuestionTypeId))
                            ->when($this->isValidUuid($this->filterCategoryQuestionId), fn ($q) => $q->where('sub_tq.category_question_id', $this->filterCategoryQuestionId));
                    }

                    $overallRows = $overallQuery->groupBy('umq.user_timetable_id')
                        ->select(
                            'umq.user_timetable_id',
                            DB::raw('COUNT(*) as total_questions'),
                            DB::raw("COUNT(CASE WHEN umq.status = 'correct' THEN 1 END) as correct"),
                            DB::raw("COUNT(CASE WHEN umq.status = 'wrong' THEN 1 END) as wrong"),
                            DB::raw("COUNT(CASE WHEN umq.timetable_answer_id IS NULL THEN 1 END) as unanswered")
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

                    $topicRows = DB::table('user_module_questions as umq')
                        ->join('timetable_questions as tq', 'tq.id', '=', 'umq.timetable_question_id')
                        ->leftJoin('topics as top', 'top.id', '=', 'tq.topic_id')
                        ->whereIn('umq.user_timetable_id', $chunkIds)
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
                }
                asort($allTopics);
            }

            $filterSummary = $this->getFilterSummary();
            $company = Auth::user()?->company ? Auth::user()->company()->with('companyDetail')->first() : \App\Models\Company\Company::with('companyDetail')->first();

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
                    'name' => $result->user_name ?? '-',
                    'nim' => $result->user_nim ?? ($result->user_username ?? '-'),
                    'module' => $result->module_name ?? '-',
                    'timetable' => $result->timetable_name ?? '-',
                    'start_time' => $result->timetable_start_time ? \Carbon\Carbon::parse($result->timetable_start_time)->format('d/m/y H:i') : '-',
                    'end_time' => $result->timetable_end_time ? \Carbon\Carbon::parse($result->timetable_end_time)->format('d/m/y H:i') : '-',
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
            $numericMarks = $examResults->pluck('mark')->filter(fn ($m) => ! is_null($m));
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
                ->setOption('isRemoteEnabled', false)
                ->setOption('isFontSubsettingEnabled', true)
                ->setOption('defaultFont', 'sans-serif')
                ->setOption('chroot', public_path())
                ->setOption('margin-top', 8)
                ->setOption('margin-right', 8)
                ->setOption('margin-bottom', 8)
                ->setOption('margin-left', 8);

            return response()->streamDownload(
                fn () => print ($pdf->output()),
                'exam-result-report-'.date('Y-m-d-H-i-s').'.pdf'
            );
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Exam Result PDF Export Error: '.$e->getMessage(), ['trace' => $e->getTraceAsString()]);
            session()->flash('error', 'Gagal membuat file PDF: '.$e->getMessage());
        }
    }

    public function exportExcel()
    {
        try {
            @ini_set('memory_limit', '1024M');
            @set_time_limit(600);

            $fileName = 'exam-result-report-'.date('YmdHis').'.xlsx';

            return Excel::download(
                new ExamResultExport(
                    $this->search,
                    $this->user_id,
                    $this->module_id,
                    $this->timetable_id,
                    [
                        'filterTopicId' => $this->filterTopicId,
                        'filterMaterialCategoryId' => $this->filterMaterialCategoryId,
                        'filterMaterialId' => $this->filterMaterialId,
                        'filterQuestionTypeId' => $this->filterQuestionTypeId,
                        'filterCategoryQuestionId' => $this->filterCategoryQuestionId,
                    ]
                ),
                $fileName
            );
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Exam Result Excel Export Error: '.$e->getMessage(), ['trace' => $e->getTraceAsString()]);
            session()->flash('error', 'Gagal mengekspor data ke Excel: '.$e->getMessage());
        }
    }

    public function getTopicStats($userTimetable)
    {
        if (isset($this->cachedTopicStatsByUser[$userTimetable->id])) {
            return $this->cachedTopicStatsByUser[$userTimetable->id];
        }

        if (! $userTimetable->relationLoaded('userModuleQuestions')) {
            return [];
        }

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
