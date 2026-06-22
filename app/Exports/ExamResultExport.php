<?php

namespace App\Exports;

use App\Models\Master\RatingScale\RatingScale;
use App\Models\User\UserTimetable;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ExamResultExport implements FromCollection, ShouldAutoSize, WithHeadings, WithMapping
{
    protected $search;
    protected $user_id;
    protected $module_id;
    protected $timetable_id;
    protected $allTopics;
    protected $ratingScales;

    public function __construct($search = '', $user_id = '', $module_id = '', $timetable_id = '')
    {
        $this->search = $search;
        $this->user_id = $user_id;
        $this->module_id = $module_id;
        $this->timetable_id = $timetable_id;
        $this->ratingScales = RatingScale::orderBy('order')->get();
        
        // Query results to find all unique topics
        $results = $this->queryResults();
        
        $this->allTopics = [];
        foreach ($results as $result) {
            foreach ($result->userModuleQuestions as $umq) {
                $tq = $umq->timetableQuestion;
                if ($tq && $tq->topic_id) {
                    $this->allTopics[$tq->topic_id] = $tq->topic?->name ?? 'Tanpa Topik';
                }
            }
        }
        asort($this->allTopics);
    }

    private function queryResults()
    {
        return UserTimetable::with(['user', 'timetable.module', 'userModuleQuestions.timetableQuestion.topic'])
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
    }

    /**
     * @return Collection
     */
    public function collection()
    {
        return $this->queryResults();
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

        $totalQuestions = $result->userModuleQuestions->count();
        $correct = $result->userModuleQuestions->where('status', 'correct')->count();
        $wrong = $result->userModuleQuestions->where('status', 'wrong')->count();
        $unanswered = $result->userModuleQuestions->whereNull('timetable_answer_id')->count();

        // Calculate topic stats
        $topicStats = [];
        foreach ($result->userModuleQuestions as $umq) {
            $tq = $umq->timetableQuestion;
            if ($tq && $tq->topic_id) {
                $topicId = $tq->topic_id;
                if (!isset($topicStats[$topicId])) {
                    $topicStats[$topicId] = [
                        'total' => 0,
                        'correct' => 0,
                        'wrong' => 0,
                        'unanswered' => 0,
                    ];
                }
                $topicStats[$topicId]['total']++;
                if (empty($umq->timetable_answer_id)) {
                    $topicStats[$topicId]['unanswered']++;
                } elseif ($umq->status === 'correct') {
                    $topicStats[$topicId]['correct']++;
                } elseif ($umq->status === 'wrong') {
                    $topicStats[$topicId]['wrong']++;
                }
            }
        }

        $row = [
            $no,
            $result->user?->name ?? '-',
            $result->user?->nim ?? ($result->user?->username ?? '-'),
            $result->timetable?->module?->name ?? '-',
            $result->timetable?->name ?? '-',
            $totalQuestions,
            $correct,
            $wrong,
            $unanswered,
        ];

        // Map topic columns
        foreach ($this->allTopics as $topicId => $topicName) {
            $stats = $topicStats[$topicId] ?? ['total' => 0, 'correct' => 0, 'wrong' => 0, 'unanswered' => 0];
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

        $row[] = $mark ?? 0;
        $row[] = $grade;

        return $row;
    }
}
