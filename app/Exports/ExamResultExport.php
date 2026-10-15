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
        // 1. Fetch only necessary user timetables and eager-load lightweight relations (no heavy userModuleQuestions)
        $this->examResults = UserTimetable::query()
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
            ->where('status', 'done')
            ->orderBy('created_at', 'desc')
            ->get();

        $userTimetableIds = $this->examResults->pluck('id');

        if ($userTimetableIds->isEmpty()) {
            return;
        }

        // 2. Aggregate overall question counts directly in the database
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
            $this->overallStats[$row->user_timetable_id] = [
                'total' => (int) $row->total_questions,
                'correct' => (int) $row->correct,
                'wrong' => (int) $row->wrong,
                'unanswered' => (int) $row->unanswered,
            ];
        }

        // 3. Aggregate topic counts directly in the database
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
            $this->allTopics[$row->topic_id] = $row->topic_name;
            $this->topicStatsByUser[$row->user_timetable_id][$row->topic_id] = [
                'total' => (int) $row->total_questions,
                'correct' => (int) $row->correct,
                'wrong' => (int) $row->wrong,
                'unanswered' => (int) $row->unanswered,
            ];
        }
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

        $row = [
            $no,
            $result->user?->name ?? '-',
            $result->user?->nim ?? ($result->user?->username ?? '-'),
            $result->timetable?->module?->name ?? '-',
            $result->timetable?->name ?? '-',
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
