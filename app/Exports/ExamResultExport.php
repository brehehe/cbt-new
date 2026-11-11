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
    protected $allTopics = [];
    protected $ratingScales;
    protected $examResults;
    protected $overallStats = [];
    protected $topicStatsByUser = [];

    public function __construct($search = '', $user_id = '', $module_id = '', $timetable_id = '')
    {
        @ini_set('memory_limit', '512M');
        @set_time_limit(300);

        $this->search = $search;
        $this->user_id = $user_id;
        $this->module_id = $module_id;
        $this->timetable_id = $timetable_id;
        $this->ratingScales = RatingScale::orderBy('order')->get();

        $this->loadData();
    }

    private function loadData()
    {
        $companyId = auth()->user()?->company_id;

        // 1. Fetch only necessary user timetables using lightweight DB::table query (no heavy model hydration)
        $query = DB::table('user_timetables as ut')
            ->leftJoin('users as u', 'u.id', '=', 'ut.user_id')
            ->leftJoin('timetables as t', 't.id', '=', 'ut.timetable_id')
            ->leftJoin('modules as m', 'm.id', '=', 't.module_id')
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
            })
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
                'm.name as module_name',
            ])
            ->orderBy('ut.created_at', 'desc');

        $this->examResults = $query->get();

        $userTimetableIds = $this->examResults->pluck('id');

        if ($userTimetableIds->isEmpty()) {
            return;
        }

        // 2. Chunking in 500-record blocks to prevent parameter limit and memory spikes
        $userTimetableIds->chunk(500)->each(function ($chunk) {
            $overallRows = DB::table('user_module_questions')
                ->whereIn('user_timetable_id', $chunk)
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
                $this->overallStats[$row->user_timetable_id] = [
                    'total' => (int) $row->total_questions,
                    'correct' => (int) $row->correct,
                    'wrong' => (int) $row->wrong,
                    'unanswered' => (int) $row->unanswered,
                ];
            }

            $topicRows = DB::table('user_module_questions as umq')
                ->join('timetable_questions as tq', 'tq.id', '=', 'umq.timetable_question_id')
                ->leftJoin('topics as top', 'top.id', '=', 'tq.topic_id')
                ->whereIn('umq.user_timetable_id', $chunk)
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
