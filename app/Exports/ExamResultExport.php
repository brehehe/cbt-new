<?php

namespace App\Exports;

use App\Models\Master\RatingScale\RatingScale;
use App\Models\User\UserTimetable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ExamResultExport implements FromCollection, WithColumnWidths, WithHeadings, WithMapping, WithStyles
{
    protected $search;
    protected $user_id;
    protected $module_id;
    protected $timetable_id;
    protected $questionFilters = [];
    protected $allTopics = [];
    protected $ratingScales;
    protected $examResults;
    protected $overallStats = [];
    protected $topicStatsByUser = [];

    public function __construct($search = '', $user_id = '', $module_id = '', $timetable_id = '', array $questionFilters = [])
    {
        @ini_set('memory_limit', '1024M');
        @set_time_limit(600);

        $this->search = $search;
        $this->user_id = $user_id;
        $this->module_id = $module_id;
        $this->timetable_id = $timetable_id;
        $this->questionFilters = $questionFilters;
        $this->ratingScales = RatingScale::orderBy('order')->get();

        $this->loadData();
    }

    private function isValidUuid($value): bool
    {
        return is_string($value) && preg_match('/^[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{12}$/', $value) === 1;
    }

    private function loadData()
    {
        $companyId = auth()->user()?->company_id;

        $filterTopicId = $this->isValidUuid($this->questionFilters['filterTopicId'] ?? null) ? $this->questionFilters['filterTopicId'] : null;
        $filterMaterialCategoryId = $this->isValidUuid($this->questionFilters['filterMaterialCategoryId'] ?? null) ? $this->questionFilters['filterMaterialCategoryId'] : null;
        $filterMaterialId = $this->isValidUuid($this->questionFilters['filterMaterialId'] ?? null) ? $this->questionFilters['filterMaterialId'] : null;
        $filterQuestionTypeId = $this->isValidUuid($this->questionFilters['filterQuestionTypeId'] ?? null) ? $this->questionFilters['filterQuestionTypeId'] : null;
        $filterCategoryQuestionId = $this->isValidUuid($this->questionFilters['filterCategoryQuestionId'] ?? null) ? $this->questionFilters['filterCategoryQuestionId'] : null;
        $hasQuestionFilter = $filterTopicId || $filterMaterialCategoryId || $filterMaterialId || $filterQuestionTypeId || $filterCategoryQuestionId;

        // 1. Fetch only necessary user timetables using lightweight DB::table query (no heavy model hydration)
        $query = DB::table('user_timetables as ut')
            ->leftJoin('users as u', 'u.id', '=', 'ut.user_id')
            ->leftJoin('timetables as t', 't.id', '=', 'ut.timetable_id')
            ->leftJoin('modules as m', 'm.id', '=', 't.module_id')
            ->whereNull('ut.deleted_at')
            ->when($companyId, fn ($q) => $q->where('ut.company_id', $companyId))
            ->when($this->user_id, fn ($q) => $q->where('ut.user_id', $this->user_id))
            ->when($this->module_id, fn ($q) => $q->where('t.module_id', $this->module_id))
            ->when($this->timetable_id, fn ($q) => $q->where('ut.timetable_id', $this->timetable_id))
            ->when($this->search, function ($q) {
                $search = '%'.$this->search.'%';
                $q->where(function ($sub) use ($search) {
                    $sub->where('u.name', 'ilike', $search)
                        ->orWhere('u.nim', 'ilike', $search)
                        ->orWhere('u.username', 'ilike', $search)
                        ->orWhere('t.name', 'ilike', $search)
                        ->orWhere('m.name', 'ilike', $search);
                });
            });

        if ($hasQuestionFilter) {
            $query->whereExists(function ($sub) use ($filterTopicId, $filterMaterialCategoryId, $filterMaterialId, $filterQuestionTypeId, $filterCategoryQuestionId) {
                $sub->select(DB::raw(1))
                    ->from('user_module_questions as sub_umq')
                    ->join('timetable_questions as sub_tq', 'sub_tq.id', '=', 'sub_umq.timetable_question_id')
                    ->whereColumn('sub_umq.user_timetable_id', 'ut.id')
                    ->when($filterTopicId, fn ($q) => $q->where('sub_tq.topic_id', $filterTopicId))
                    ->when($filterMaterialCategoryId, fn ($q) => $q->where('sub_tq.material_category_id', $filterMaterialCategoryId))
                    ->when($filterMaterialId, fn ($q) => $q->where('sub_tq.material_id', $filterMaterialId))
                    ->when($filterQuestionTypeId, fn ($q) => $q->where('sub_tq.question_type_id', $filterQuestionTypeId))
                    ->when($filterCategoryQuestionId, fn ($q) => $q->where('sub_tq.category_question_id', $filterCategoryQuestionId));
            });
        }

        $this->examResults = $query->select([
                'ut.id',
                'ut.user_id',
                'ut.timetable_id',
                'ut.mark',
                'ut.created_at',
                'u.name as user_name',
                'u.nim as user_nim',
                'u.username as user_username',
                't.name as timetable_name',
                'm.name as module_name',
            ])
            ->orderBy('ut.created_at', 'desc')
            ->get();

        $userTimetableIds = $this->examResults->pluck('id');

        if ($userTimetableIds->isEmpty()) {
            return;
        }

        // 2. Chunking in 500-record blocks to prevent parameter limit and memory spikes
        $userTimetableIds->chunk(500)->each(function ($chunk) use ($hasQuestionFilter, $filterTopicId, $filterMaterialCategoryId, $filterMaterialId, $filterQuestionTypeId, $filterCategoryQuestionId) {
            $overallQuery = DB::table('user_module_questions as umq')
                ->whereIn('umq.user_timetable_id', $chunk)
                ->whereNull('umq.deleted_at');

            if ($hasQuestionFilter) {
                $overallQuery->join('timetable_questions as sub_tq', 'sub_tq.id', '=', 'umq.timetable_question_id')
                    ->when($filterTopicId, fn ($q) => $q->where('sub_tq.topic_id', $filterTopicId))
                    ->when($filterMaterialCategoryId, fn ($q) => $q->where('sub_tq.material_category_id', $filterMaterialCategoryId))
                    ->when($filterMaterialId, fn ($q) => $q->where('sub_tq.material_id', $filterMaterialId))
                    ->when($filterQuestionTypeId, fn ($q) => $q->where('sub_tq.question_type_id', $filterQuestionTypeId))
                    ->when($filterCategoryQuestionId, fn ($q) => $q->where('sub_tq.category_question_id', $filterCategoryQuestionId));
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
                $this->overallStats[$row->user_timetable_id] = [
                    'total' => (int) $row->total_questions,
                    'correct' => (int) $row->correct,
                    'wrong' => (int) $row->wrong,
                    'unanswered' => (int) $row->unanswered,
                ];
            }

            $topicQuery = DB::table('user_module_questions as umq')
                ->join('timetable_questions as tq', 'tq.id', '=', 'umq.timetable_question_id')
                ->leftJoin('topics as top', 'top.id', '=', 'tq.topic_id')
                ->whereIn('umq.user_timetable_id', $chunk)
                ->whereNull('umq.deleted_at')
                ->whereNotNull('tq.topic_id');

            if ($hasQuestionFilter) {
                $topicQuery->when($filterTopicId, fn ($q) => $q->where('tq.topic_id', $filterTopicId))
                    ->when($filterMaterialCategoryId, fn ($q) => $q->where('tq.material_category_id', $filterMaterialCategoryId))
                    ->when($filterMaterialId, fn ($q) => $q->where('tq.material_id', $filterMaterialId))
                    ->when($filterQuestionTypeId, fn ($q) => $q->where('tq.question_type_id', $filterQuestionTypeId))
                    ->when($filterCategoryQuestionId, fn ($q) => $q->where('tq.category_question_id', $filterCategoryQuestionId));
            }

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
                $this->allTopics[$row->topic_id] = $row->topic_name;
                $this->topicStatsByUser[$row->user_timetable_id][$row->topic_id] = [
                    'total' => (int) $row->total_questions,
                    'correct' => (int) $row->correct,
                    'wrong' => (int) $row->wrong,
                    'unanswered' => (int) $row->unanswered,
                ];
            }
        });

        asort($this->allTopics);
    }

    /**
     * @return Collection
     */
    public function collection()
    {
        return $this->examResults;
    }

    public function headings(): array
    {
        $headers = [
            'No',
            'Nama Mahasiswa',
            'NIM/Username',
            'Modul',
            'Jadwal Ujian',
            'Total Soal',
            'Benar',
            'Salah',
            'Tidak Dijawab',
        ];

        // Add topic headings
        foreach ($this->allTopics as $topicId => $topicName) {
            $headers[] = $topicName . ' (Total Soal)';
            $headers[] = $topicName . ' (Benar)';
            $headers[] = $topicName . ' (Salah)';
            $headers[] = $topicName . ' (Tidak Dijawab)';
        }

        $headers[] = 'Nilai';
        $headers[] = 'Grade';

        return $headers;
    }

    /**
     * @param mixed $result
     */
    public function map($result): array
    {
        static $no = 0;
        $no++;

        $overall = $this->overallStats[$result->id] ?? [
            'total' => 0,
            'correct' => 0,
            'wrong' => 0,
            'unanswered' => 0,
        ];

        $userName = $result->user_name ?? ($result->user?->name ?? '-');
        $userNim = $result->user_nim ?? ($result->user_username ?? ($result->user?->nim ?? ($result->user?->username ?? '-')));
        $moduleName = $result->module_name ?? ($result->timetable?->module?->name ?? '-');
        $timetableName = $result->timetable_name ?? ($result->timetable?->name ?? '-');

        $row = [
            $no,
            $userName,
            $userNim,
            $moduleName,
            $timetableName,
            $overall['total'],
            $overall['correct'],
            $overall['wrong'],
            $overall['unanswered'],
        ];

        // Map topic columns
        foreach ($this->allTopics as $topicId => $topicName) {
            $stats = $this->topicStatsByUser[$result->id][$topicId] ?? [
                'total' => 0,
                'correct' => 0,
                'wrong' => 0,
                'unanswered' => 0,
            ];
            $row[] = $stats['total'];
            $row[] = $stats['correct'];
            $row[] = $stats['wrong'];
            $row[] = $stats['unanswered'];
        }

        $mark = $result->mark;
        $grade = '-';
        if ($mark !== null) {
            $scale = $this->ratingScales->first(function ($item) use ($mark) {
                return $item->min_score <= $mark && $item->max_score >= $mark;
            });
            $grade = $scale?->grade_letter ?? '-';
        }

        $row[] = $mark !== null ? (float) $mark : 0;
        $row[] = $grade;

        return $row;
    }

    public function columnWidths(): array
    {
        return [
            'A' => 6,   // No
            'B' => 28,  // Nama Mahasiswa
            'C' => 18,  // NIM/Username
            'D' => 24,  // Modul
            'E' => 24,  // Jadwal Ujian
            'F' => 12,  // Total Soal
            'G' => 10,  // Benar
            'H' => 10,  // Salah
            'I' => 14,  // Tidak Dijawab
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FF1F2937'],
                ],
            ],
        ];
    }
}
